<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\API\Admin\Courses\CoursesController;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\StudentTransaction;
use App\Models\Student;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;


class StudentTransactionController extends BaseController
{
    public function getStudentTransactionList(Request $request)
    {
        $transactions = StudentTransaction::where('student_id', $request->input('student')->id)
            ->get()->map(function($transaction) use ($request){
                return [
                    'id' => $transaction->id,
                    'title' => $transaction->title,
                    'price' => $transaction->price,
                    'course_id' => $transaction->course_id,
                    'course_title' => $transaction->course_title,
                    'portal' => $transaction->portal,
                    'redirect_url' => $transaction->redirect_url,
                    'success' => $transaction->success,
                    'order_no' => $transaction->order_no,
                    'ref_id' => $transaction->ref_id,
                    'date' => $transaction->updated_at,
                    'error_msg' => $transaction->error_msg,
                    'name' => $request->input('student')->first_name . " " . $request->input('student')->last_name
                ];
            });

        return $this->sendResponse(Constant::$SUCCESS, $transactions);
    }

    public function getStudentTransaction(Request $request)
    {
        $transaction = StudentTransaction::find($request->input('transaction_id'));

        $result = [
            'id' => $transaction->id,
            'title' => $transaction->title,
            'price' => $transaction->price,
            'course_id' => $transaction->course_id,
            'course_title' => $transaction->course_title,
            'portal' => $transaction->portal,
            'redirect_url' => $transaction->redirect_url,
            'success' => $transaction->success,
            'order_no' => $transaction->order_no,
            'ref_id' => $transaction->ref_id,
            'date' => $transaction->updated_at,
            'error_msg' => $transaction->error_msg,
            'name' => $request->input('student')->first_name . " " . $request->input('student')->last_name
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

    public function generateStudentTransaction(Request $request){
        $transaction = new StudentTransaction();
        $transaction->order_no = $this->getOrderNo();
        $transaction->title = $request->input('title'); 
        $transaction->price = $request->input('price');
        $transaction->course_id = $request->input('course_id');
        $transaction->student_id = $request->student->id;
        $transaction->course_title = $request->input('course_title');
        $transaction->portal = $request->input('portal');
        $transaction->redirect_url = $request->input('redirect_url');
        $transaction->save();

        $result = [
            'id' => $transaction->id,
            'title' => $transaction->title,
            'price' => $transaction->price,
            'course_id' => $transaction->course_id,
            'course_title' => $transaction->course_title,
            'portal' => $transaction->portal,
            'redirect_url' => $transaction->redirect_url,
            'success' => $transaction->success,
            'order_no' => $transaction->order_no,
            'ref_id' => $transaction->ref_id,
            'name' => $request->input('student')->first_name . " " . $request->input('student')->last_name
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

    public function payForCourse(Request $request)
    {
        $transaction = StudentTransaction::find($request->query('transaction_id'));
        $invoice = (new Invoice)->amount($transaction->price);

        $callback_url = "http://" . env('APP_URL') .
                        "/api/tenant/student/course/pay/done?tenant=" .
                        tenant()->id .
                        "&token=" .
                        $request->input('student')->token .
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

    public function payForCourseIsDone(Request $request)
    {
        $transaction = StudentTransaction::find($request->query('transaction_id'));

        if ($transaction) {
            try{
                $receipt = Payment::amount($transaction->price)->transactionId($transaction->ref_id)->verify();
                $transaction->ref_id = $receipt->getReferenceId();
                $transaction->success = 1;
                $transaction->save();

                // apply transaction result
                $cc = new CoursesController();
                $cc->addStudentToCourse(
                    Student::find($transaction->student_id),
                    Course::find($transaction->course_id),
                    Constant::$REGISTRATION_TYPE_WEBSITE
                );               
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
            . tenant()->id);
    }

    private function getOrderNo()
    {
        if (StudentTransaction::count() > 0)
            return StudentTransaction::max('order_no') + 1;
        else
            return 111111;
    }
}
