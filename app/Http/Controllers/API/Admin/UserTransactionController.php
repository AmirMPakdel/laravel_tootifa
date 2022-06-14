<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\UserTransaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;


class UserTransactionController extends BaseController
{
    public function getUserTransaction(Request $request)
    {
        $transaction = UserTransaction::find($request->input('transaction_id'));

        $result = [
            'id' => $transaction->id,
            'title' => $transaction->title,
            'price' => $transaction->price,
            'pt' => $transaction->pt,
            'prt' => $transaction->prt,
            'value' => $transaction->value,
            'days' => $transaction->days,
            'portal' => $transaction->portal,
            'redirect_url' => $transaction->redirect_url,
            'success' => $transaction->success,
            'order_no' => $transaction->order_no,
            'ref_id' => $transaction->ref_id,
            'date' => $transaction->updated_at,
            'error_msg' => $transaction->error_msg,
            'name' => $request->input('user')->first_name . " " . $request->input('user')->last_name
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

    public function generateUserTransaction(Request $request){
        $transaction = new UserTransaction();
        $transaction->order_no = $this->getOrderNo();
        $transaction->title = $request->input('title'); //$this->generateTransactionTitle($pt, $prt, $value, $days);
        $transaction->price = $request->input('price');
        $transaction->pt = $request->input('pt');
        $transaction->prt = $request->input('prt');
        $transaction->value = $request->input('value');
        $transaction->days = $request->input('days');
        $transaction->portal = $request->input('portal');
        $transaction->redirect_url = $request->input('redirect_url');
        $transaction->save();

        $result = [
            'id' => $transaction->id,
            'title' => $transaction->title,
            'price' => $transaction->price,
            'pt' => $transaction->pt,
            'prt' => $transaction->prt,
            'value' => $transaction->value,
            'days' => $transaction->days,
            'portal' => $transaction->portal,
            'redirect_url' => $transaction->redirect_url,
            'success' => $transaction->success,
            'order_no' => $transaction->order_no,
            'ref_id' => $transaction->ref_id,
            'name' => $request->input('user')->first_name . " " . $request->input('user')->last_name
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

    public function payForProduct(Request $request)
    {
        $transaction = UserTransaction::find($request->query('transaction_id'));
        $invoice = (new Invoice)->amount($transaction->price);

        $callback_url = "http://" . env('APP_URL') .
                        "/api/tenant/user/product/pay/done?tenant=" .
                        $request->input('user')->tenant_id .
                        "&token=" .
                        $request->input('user')->token .
                        "&transaction_id=" .
                        $transaction->id;

        return Payment::via($transaction->portal)
                ->callbackUrl($callback_url)
                ->purchase($invoice, function($driver, $transaction_id) use($transaction,$invoice){

            $transaction->uuid = $invoice->getUuid();
            $transaction->invoice_transaction_id = $transaction_id;
            $transaction->save();

        })->pay()->render();
    }

    public function payForProductIsDone(Request $request)
    {
        $transaction = UserTransaction::find($request->query('transaction_id'));

        if ($transaction) {
            try{
                $receipt = Payment::amount($transaction->price)->transactionId($transaction->ref_id)->verify();
                $transaction->ref_id = $receipt->getReferenceId();
                $transaction->success = 1;
                $transaction->save();

                // apply transaction result
                $profile = $request->user->u_profile;
                $this->setProduct($transaction, $profile);                
            }catch(Exception $e){
                $transaction->success = 0;
                $transaction->error_msg = $e->getMessage();
                $transaction->save();
            }
        }else {
            return "TRANSACTION NOT FOUND";
        }

        return Redirect::to(
            $transaction->redirect_url 
            . '/?transaction_id=' . $transaction->id 
            . '&tenant=' 
            . $request->user->tenant_id);
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
