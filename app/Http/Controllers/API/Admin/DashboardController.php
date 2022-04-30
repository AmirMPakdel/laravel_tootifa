<?php

namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\Course;
use App\Models\DailyMaintenanceCostReport;
use App\Models\StudentTransaction;
use App\Models\UserTransaction;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class DashboardController extends BaseController
{
    public function loadDashboardMainInfo(Request $request){
        $prices = StudentTransaction::where("success",1)->get('price')->toArray();
        $total_courses_count = Course::all()->count();

        $balance = $request->user->u_profile->m_balance;
        $report = DailyMaintenanceCostReport::latest()->first();

        $result = [
            'total_income' => array_sum($prices),
            'total_sell_count' => sizeof($prices),
            'total_courses_count' => $total_courses_count,
            'daily_cost' => $report->total_cost,
            'remaining_days' => floor($balance / $report->total_cost)
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

    public function getRecords(Request $request){
        $filter = $request->input('filter');
        $result = [];

        switch($filter){
            case Constant::$RECORDS_FILTER_SELLS:
                $result = StudentTransaction::all()->get('price','title','created_at');
                break;
            case Constant::$RECORDS_FILTER_INCREASE_M_BALANCE:
                $result = UserTransaction::where([
                    ['pt', Constant::$PT_INCREMENTAL],
                    ['prt', Constant::$PRT_MAINTENANCE],
                ])->get('created_at', 'price');
                break;
            case Constant::$RECORDS_FILTER_DECREASE_M_BALANCE:
                $result = DailyMaintenanceCostReport::all()->get('created_at', 'price');
                break;
        }
    
        return $this->sendResponse(Constant::$SUCCESS, $result);

    }

   
}
