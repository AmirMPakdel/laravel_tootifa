<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Admin\Courses\CoursesController;
use App\Includes\Constant;
use App\Models\StudentTransaction;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Zarinpal\Zarinpal;


class StudentTransactionController extends BaseController
{
    public function payForCourse(Request $request)
    {
        $student = $request->student;
        $username = tenant()->id;
        $title = $request->query('title');
        $course_id = $request->query('ci');
        $price = $request->query('price');

        // creating transaction
        $transaction = new StudentTransaction();
        $transaction->order_no = $this->getOrderNo();
        $transaction->title = $title; //$this->generateTransactionTitle($pt, $prt, $value, $days);
        $transaction->price = $price;
        $transaction->course_id = $course_id;
        $transaction->student_id = $student->id;

        $zarinpal = new Zarinpal(env('ZARINPAL_STUDENT_TRANSACTIONS_CODE'));
        $zarinpal->enableSandbox(); // active sandbox mod for test env
        // $zarinpal->isZarinGate(); // active zarinGate mode
        $results = $zarinpal->request(
            env('APP_URL') . "/api/product/pay/done?token={$student->token}&tenant={$username}",
            $price,
            $title,
            $student->email,
            $student->phone_number
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
        $zarinpal = new Zarinpal(env('ZARINPAL_STUDENT_TRANSACTIONS_CODE'));
        $authority = file_get_contents('Authority');
        $transaction = StudentTransaction::where('authority', $authority)->first();

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
                $cc = new CoursesController();
                $cc->addStudentToCourse(
                    Student::find($transaction->student_id),
                    Course::find($transaction->course_id),
                    Constant::$REGISTRATION_TYPE_WEBSITE
                );
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
        if (StudentTransaction::count() > 0)
            return StudentTransaction::max('order_no') + 1;
        else
            return 111111;
    }
}
