<?php

namespace App\Http\Controllers\API;

use App\Includes\Constant;
use App\Models\UserTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Zarinpal\Zarinpal;


class UserTransactionController extends BaseController
{
    public function payForProduct(Request $request)
    {
        $user = $request->user;
        $username = tenant()->id;
        $title = $request->query('title');
        $pt = $request->query('pt');
        $prt = $request->query('prt');
        $price = $request->query('price');
        $value = $request->query('value');
        $days = $request->query('days');

        // creating transaction
        $transaction = new UserTransaction();
        $transaction->order_no = $this->getOrderNo();
        $transaction->title = $title; //$this->generateTransactionTitle($pt, $prt, $value, $days);
        $transaction->price = $price;
        $transaction->pt = $pt;
        $transaction->prt = $prt;
        $transaction->value = $value;
        $transaction->days = $days;

        $zarinpal = new Zarinpal(env('ZARINPAL_USER_TRANSACTIONS_CODE'));
        $zarinpal->enableSandbox(); // active sandbox mod for test env
        // $zarinpal->isZarinGate(); // active zarinGate mode
        $results = $zarinpal->request(
            env('APP_URL') . "/api/product/pay/done?token={$user->token}&tenant={$username}",
            $price,
            $title,
            $user->email,
            $user->phone_number
        );

        if (isset($results['Authority'])) {
            file_put_contents('Authority', $results['Authority']);
            $transaction->authority = $results['Authority'];
            $transaction->save();
            $zarinpal->redirect();
        }

      
        return "Authority not found!";
    }

    public function payForProductIsDone(Request $request)
    {
        $zarinpal = new Zarinpal(env('ZARINPAL_USER_TRANSACTIONS_CODE'));
        $authority = file_get_contents('Authority');
        $transaction = UserTransaction::where('authority', $authority)->first();

        if ($transaction) {
            $result = $zarinpal->verify('OK', 1000, $authority);
            if ($result['Status'] == 'success') {
                // updating transaction
                $transaction->success = 1;
                $transaction->issue_tracking_no = $result['RefID'];
                $transaction->card_pan_mask = $result['ExtraDetail']['Transaction']['CardPanMask'];
                $transaction->card_pan_hash = $result['ExtraDetail']['Transaction']['CardPanHash'];
                $transaction->save();

                // apply transaction result
                $profile = $request->user->u_profile;
                $this->setProduct($transaction, $profile);
            } else {
                $transaction->success = 0;
                $transaction->save();
            }
        }

        // TODO redirect to a url which presents transaction's status
        return Redirect::to(env('APP_URL') . '/dashboard/' . $transaction->id . '/transaction');
    }

    private function getOrderNo()
    {
        if (UserTransaction::count() > 0)
            return UserTransaction::max('order_no') + 1;
        else
            return 111111;
    }

    private function generateTransactionTitle($pt, $prt, $value, $days)
    {
        $title = "خرید";
        if ($pt == Constant::$PT_INCREMENTAL) {
            if ($prt == Constant::$PRT_SMS)
                $title = "{$value} تومان اعتبار ارسال پیامک";
            else if ($prt == Constant::$PRT_MAINTENANCE)
                $title = "{$value} تومان اعتبار نگهداری";
            else if ($prt == Constant::$PRT_TEST)
                $title = "بسته {$value} عددی برگزاری آزمون";
        } else if ($pt == Constant::$PT_ACTIVATION) {
            if ($prt == Constant::$PRT_TEST)
                $title = "بسته {$days} روزه برگزاری آزمون";
        }

        return $title;
    }

    private function setProduct($transaction, $profile)
    {
        if ($transaction->pt == Constant::$PT_INCREMENTAL) {
            if ($transaction->prt == Constant::$PRT_SMS)
                $profile->s_balance += $transaction->value;
            else if ($transaction->prt == Constant::$PRT_MAINTENANCE)
                $profile->m_balance += $transaction->value;
            else if ($transaction->prt == Constant::$PRT_TEST)
                $profile->holdable_test_count += $transaction->value;
        } else if ($transaction->pt == Constant::$PT_ACTIVATION) {
            if ($transaction->prt == Constant::$PRT_TEST)
                $profile->infinit_test_finish_date = Carbon::now()->addDays($transaction->days);
        }

        $profile->save();
    }
}
