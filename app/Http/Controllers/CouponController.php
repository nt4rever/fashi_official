<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Constraint\Count;


class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_coupon = Coupon::latest()->paginate(10);
        return view('admin.coupon.all_coupon', compact('all_coupon'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.coupon.add_coupon');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "coupon_name" => "required|max:255",
            "coupon_code" => "required|max:255",
            "coupon_time" => "required|integer",
            "coupon_condition" => "required|integer",
            "coupon_number" => "required|numeric",
            "coupon_date_time" => "required",
        ]);

        $check = Coupon::where('coupon_code', $request->coupon_code)->first();
        if ($check) {
            Session::flash('message', "Mã giảm giá đã tồn tại!");
            return Redirect::to('ad/view-coupon');
        }
        $coupon = new Coupon();
        $coupon->coupon_name = $request->coupon_name;
        $coupon->coupon_code = $request->coupon_code;
        $coupon->coupon_time = $request->coupon_time;
        $coupon->coupon_number = $request->coupon_number;
        $coupon->coupon_condition = $request->coupon_condition;
        if ($request->coupon_condition == 1 && $request->coupon_number > 100) {
            Session::flash('message', "Thất bại!");
            return Redirect::to('ad/view-coupon');
        }
        $date = explode(" - ", $request->input('coupon_date_time'));
        $coupon->coupon_start = $date[0];
        $coupon->coupon_end = $date[1];
        $coupon->coupon_status = $request->coupon_status;
        $coupon->save();
        Session::flash('message', "Thêm mã giảm giá thành công!");
        return Redirect::to('ad/view-coupon');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon.edit_coupon', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "coupon_name" => "required|max:255",
            "coupon_code" => "required|max:255",
            "coupon_time" => "required|integer",
            "coupon_condition" => "required|integer",
            "coupon_number" => "required|numeric",
            "coupon_date_time" => "required",
        ]);

        $coupon = Coupon::find($id);
        $coupon->coupon_name = $request->coupon_name;
        $coupon->coupon_code = $request->coupon_code;
        $coupon->coupon_time = $request->coupon_time;
        $coupon->coupon_number = $request->coupon_number;
        $coupon->coupon_condition = $request->coupon_condition;
        if ($request->coupon_condition == 1 && $request->coupon_number > 100) {
            Session::flash('message', "Thất bại!");
            return Redirect::to('ad/view-coupon');
        }

        $date = explode(" - ", $request->input('coupon_date_time'));
        $coupon->coupon_start = $date[0];
        $coupon->coupon_end = $date[1];
        $coupon->coupon_status = $request->coupon_status;
        $coupon->save();
        Session::flash('message', "Thay đổi mã giảm giá thành công!");
        return Redirect::to('ad/view-coupon');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        Session::flash('message', "Xoá mã giảm giá thành công!");
        return Redirect::to('ad/view-coupon');
    }
}
