<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Post;
use App\Models\Product;
use App\Models\Statistic;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    public function AuthLogin()
    {
        $login = Auth::id();
        if ($login) {
            return Redirect::to('/dashboard');
        } else {
            return Redirect::to('/login-auth')->send();
        }
    }

    public function show_dashboard()
    {
        $this->AuthLogin();
        $users = DB::table('users')->get();
        $online = 0;
        foreach ($users as $user) {
            if (Cache::has('admin' . $user->id)) {
                $online++;
            }
        }
        $customers = Customer::all();
        $online_customer = 0;
        foreach ($customers as $item) {
            if (Cache::has('user' . $item->customer_id)) {
                $online_customer++;
            }
        }
        $visitor = DB::table('tbl_visitor')->get();
        $visitor = $visitor->count();
        $last_7_day = Statistic::whereBetWeen('order_date', [Carbon::now()->subDays(6)->toDateString(), Carbon::now()->toDateString()]);
        $last_7_day = $last_7_day->sum('total_order');
        $product = Product::orderBy('product_views', 'desc')->get();
        $post  = Post::orderBy('post_views', 'desc')->get();
        return view('admin.dashboard', compact('product', 'post', 'online', 'online_customer', 'visitor', 'last_7_day'));
    }

    public function login_dashboard()
    {
        $login = Auth::id();
        if ($login) {
            return Redirect::to('/dashboard');
        }
        return view('admin.custom_auth.login_auth');
    }

    public function logout_dashboard()
    {
        Auth::logout();
        return Redirect::to('/login-auth');
    }

    //manage account customer
    public function admin_account_customer()
    {
        $this->AuthLogin();
        $all_account  = Customer::orderBy('customer_id', 'desc')->paginate(25);
        return view('admin.account.customer', compact('all_account'));
    }

    public function admin_account_customer_change_password(Request $request)
    {
        $this->AuthLogin();
        try {
            $id = $request->id;
            $password = md5($request->password);
            Customer::where('customer_id', $id)->update(['customer_password' => $password]);
            echo "true";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function admin_account_customer_block(Request $request)
    {
        $this->AuthLogin();
        try {
            $id = $request->id;
            $cus =  Customer::findOrFail($id);
            if ($cus->customer_status == 0) {
                $cus->customer_status = 1;
                $cus->save();
                $data['status'] = '<span class="badge badge-secondary">Khoá</span>';
                $data['button'] = '<button class="btn btn-outline-warning change-password" title="Đổi mật khẩu"
                data-id="' . $cus->customer_id . '"><i class="fas fa-key"></i></button>
                <button class="btn btn-outline-info block-account" title="Khoá tài khoản"
                data-id="' . $cus->customer_id . '"><i class="fas fa-unlock"></i></button>';
                return json_encode($data);
            } else {
                $cus->customer_status = 0;
                $cus->save();
                $data['status'] =  ' <span class="badge badge-success">Hoạt động</span>';
                $data['button'] = '<button class="btn btn-outline-warning change-password" title="Đổi mật khẩu"
                data-id="' . $cus->customer_id . '"><i class="fas fa-key"></i></button>
                <button class="btn btn-outline-danger block-account" title="Khoá tài khoản"
                data-id="' . $cus->customer_id . '"><i class="fas fa-ban"></i></button>';
                return json_encode($data);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    // end manage account customer
}
