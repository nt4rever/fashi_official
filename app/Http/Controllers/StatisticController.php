<?php

namespace App\Http\Controllers;

use App\Exports\StatisticExports;
use App\Models\Order;
use App\Models\Statistic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\Cast\Array_;

class StatisticController extends Controller
{
    public function AuthLogin()
    {
        $login = Auth::id();
        if ($login) {
            return Redirect::to('/dashboard');
        } else {
            return Redirect::to('/admin')->send();
        }
    }

    public function today_statistic()
    {
        $this->AuthLogin();
        $this_day = Statistic::where('order_date', Carbon::now()->toDateString())->first();
        if ($this_day) {
            $day['sum']  = $this_day->sales;
            $day['order'] = $this_day->total_order;
        } else {
            $day['sum']  = 0;
            $day['order'] = 0;
        }
        $this_week = Statistic::whereBetWeen('order_date', [Carbon::now()->subDays(6)->toDateString(), Carbon::now()->toDateString()]);
        $week['sum'] = $this_week->sum('sales');
        $week['order'] = $this_week->sum('total_order');
        return view('admin.statistic.today_statistic', compact('day', 'week'));
    }

    public function total_statistic()
    {
        $this->AuthLogin();
        $sum_order = Order::all()->count();
        $sum_order_pending = Order::where('order_status', 0)->get()->count();
        $sum_order_confirm = Order::whereIn('order_status', [1, 2])->get()->count();
        $sum_order_fail = Order::where('order_status', -1)->get()->count();
        $sum_sales = Statistic::sum('sales');
        $sum_sales_week = Statistic::whereBetWeen('order_date', [Carbon::now()->subDays(7)->toDateString(), Carbon::now()->toDateString()])->sum('sales');
        $sum_sales_month  = Statistic::whereBetWeen('order_date', [Carbon::now()->subDays(30)->toDateString(), Carbon::now()->toDateString()])->sum('sales');
        $sum_today = Statistic::where('order_date', Carbon::now()->toDateString())->first();
        if (!$sum_today) {
            $sum_today = 0;
        } else {
            $sum_today = $sum_today->sales;
        }
        return view('admin.statistic.total_statistic', compact('sum_sales', 'sum_order', 'sum_order_pending', 'sum_order_confirm', 'sum_order_fail', 'sum_sales_week', 'sum_sales_month', 'sum_today'));
    }

    public function filte_by_date(Request $request)
    {
        $this->AuthLogin();
        $get = Statistic::whereBetween('order_date', [$request->start, $request->end])->orderBy('order_date', 'asc')->get();
        foreach ($get as $item) {
            $chart_data[] = array(
                'preiod' => $item->order_date,
                'order' => $item->total_order,
                'sales' => $item->sales,
                'profit' => $item->profit,
                'quantity' => $item->quantity
            );
        }
        if (isset($chart_data)) {
            echo json_encode($chart_data);
        }
    }

    public function dashboard_chart()
    {
        $this->AuthLogin();
        $get = Statistic::whereBetween('order_date', [Carbon::now()->subDays(7)->toDateString(), Carbon::now()->toDateString()])->orderBy('order_date', 'asc')->get();
        foreach ($get as $item) {
            $chart_data[] = array(
                'preiod' => $item->order_date,
                'order' => $item->total_order,
                'sales' => $item->sales,
                'profit' => $item->profit,
                'quantity' => $item->quantity
            );
        }
        if (isset($chart_data)) {
            echo json_encode($chart_data);
        }
    }

    public function to_date()
    {

        $total = Order::select(DB::raw("sum(replace(order_total,',','')) as sum,date"))->where('order_status', 1)->groupBy('date')->get();
        $count = Order::join('tbl_order_detail', 'tbl_order.order_id', 'tbl_order_detail.order_id')->select(DB::raw('sum(product_sales_quantity) as quantity, date'))->where('order_status', 1)->groupBy('date')->get();
        $count_order = Order::select('date', DB::raw('count(*) as "order"'))->where('order_status', 1)->groupBy('date')->get();
        for ($i = 0; $i < sizeof($total); $i++) {
            $data[] = array(
                'order_date' => $total[$i]->date,
                'total_order' => $count_order[$i]->order,
                'quantity' => $count[$i]->quantity,
                'sales' => $total[$i]->sum
            );
        }
        echo json_encode($data);
    }

    public function export_csv()
    {
        $this->AuthLogin();
        $start = $_GET['start'];
        $end = $_GET['end'];
        if (!empty($start) || !empty($end)) {
            return Excel::download(new StatisticExports($start, $end), 'statistic-'.date('Ymdhis').'.xlsx');
        } else {
            return abort('looix');
        }
    }
}
