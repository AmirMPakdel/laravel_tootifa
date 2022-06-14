<?php

namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Models\Course;
use App\Models\DailyMaintenanceCostReport;
use App\Models\Student;
use App\Models\StudentTransaction;
use App\Models\UserTransaction;
use DateTime;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    public function loadDashboardMainInfo(Request $request){
        $prices = StudentTransaction::where("success",1)->get('price')->toArray();
        $total_courses_count = Course::all()->count();

        $balance = $request->user->u_profile->m_balance;
        $result = Helper::calculateUsersTotalMaintenanceCost();
        $remaining_days = ($result['total_cost']) ? floor($balance / $result['total_cost']) : null;

        $result = [
            'prices' => $prices,
            'total_income' => array_sum($prices),
            'total_sell_count' => sizeof($prices),
            'total_courses_count' => $total_courses_count,
            'daily_cost' => $result['total_cost'],
            'remaining_days' => $remaining_days, // null means forever
            'balance' => $balance,
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }


    public function loadIncomeChart(Request $request){
        $filter = $request->input('filter');
        $date = DateTime::createFromFormat('Ymd', '20010912');
        switch($filter){
            case Constant::$INCOME_CHART_FILTER_YEAR:
                $date = new DateTime('365 days ago');
                break;
            case Constant::$INCOME_CHART_FILTER_MONTH:
                $date = new DateTime('30 days ago');
                break;
            case Constant::$INCOME_CHART_FILTER_WEEK:
                $date = new DateTime('7 days ago');
                break;
        }

        $incomes = StudentTransaction::where([
            ["success",1],
            ["created_at", '>=', $date]
        ])->get('price', 'created_at');

        
        $result = [];
        foreach($incomes as $income){
            $result[$income->created_at] += $income;
        }

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }


    public function getRecords(Request $request, $chunk_count, $page_count){
        $filter = $request->input('filter');
        $result = [];

        switch($filter){
            case Constant::$RECORDS_FILTER_SELLS:
                $paginator = StudentTransaction::orderBy('id', 'DESC')->paginate($chunk_count, ['*'], 'page', $page_count);
                $items = $paginator->map(function($item) {
                    return [
                        'id' => $item->id,
                        'price' => $item->price,
                        'title' => $item->title,
                        'success' => $item->success,
                        'created_at' => $item->created_at,
                    ];
                });
                $result = ["total_size" => $paginator->total(), "list" => $items];
                break;
            case Constant::$RECORDS_FILTER_INCREASE_M_BALANCE: 
                $paginator = UserTransaction::where([
                    ['pt', Constant::$PT_INCREMENTAL],
                    ['prt', Constant::$PRT_MAINTENANCE],
                ])->orderBy('id', 'DESC')->paginate($chunk_count, ['*'], 'page', $page_count);
                $items = $paginator->map(function($item) {
                    return [
                        'id' => $item->id,
                        'price' => $item->price,
                        'title' => Constant::$M_BALANCE_INCREASE_DIRECT_PAYMENT,
                        'success' => $item->success,
                        'created_at' => $item->created_at,
                    ];
                });
                $result = ["total_size" => $paginator->total(), "list" => $items];
                break;
            case Constant::$RECORDS_FILTER_DECREASE_M_BALANCE:
                $paginator = DailyMaintenanceCostReport::orderBy('id', 'DESC')->paginate($chunk_count, ['*'], 'page', $page_count);
                $items = $paginator->map(function($item) {
                    return [
                        'id' => $item->id,
                        'total_cost' => $item->total_cost,
                        'created_at' => $item->created_at,
                    ];
                });
                $result = ["total_size" => $paginator->total(), "list" => $items];
                break;
        }
    
        return $this->sendResponse(Constant::$SUCCESS, $result);

    }


    public function loadStudentTransaction(Request $request)
    {
        $transaction = StudentTransaction::find($request->input('transaction_id'));
        $student = Student::find($transaction->student_id);
        if(isset($student))
            $name = $student->first_name . " " . $student->last_name;
        else
            $name = "یافت نشد";

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
            'name' => $name
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

   
}
