<?php

namespace App\Http\Controllers;

use App\Models\DealProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deal = DealProduct::paginate(9);

        return view('admin.deal.index', compact('deal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = Product::where('product_status', 0)->where('product_quantity', '>', 0)->get();
        return view('admin.deal.add', compact('product'));
    }

    public function get_product(Request $request)
    {
        $product = Product::find($request->product_id);
        return json_encode($product);
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
            'product_id' => 'required',
            'deal_desc' => 'required',
            'price_discount' => 'required',
            'price' => 'required',
            'deal_time' => 'required|date_format:Y-m-d H:i:s|after:today'
        ]);
        $price_discount = filter_var($request->price_discount, FILTER_SANITIZE_NUMBER_INT);
        $price = filter_var($request->price, FILTER_SANITIZE_NUMBER_INT);

        if ($price_discount >= $price) {
            return Redirect::to('/ad/deal')->with('message', 'Giá deal không đúng!');
        }
        $product = Product::findOrFail($request->product_id);
        $product->product_price_discount = $price_discount;
        $product->save();
        $deal = new DealProduct();
        $deal->product_id = $request->product_id;
        $deal->deal_desc = $request->deal_desc;
        $deal->deal_time = $request->deal_time;
        $deal->save();
        return Redirect::to('/ad/deal')->with('message', 'Thêm deal thành công!');
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
        $product = Product::where('product_status', 0)->where('product_quantity', '>', 0)->get();
        $deal = DealProduct::findOrFail($id);
        return view('admin.deal.edit', compact("deal", 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'product_id' => 'required',
            'deal_desc' => 'required',
            'price_discount' => 'required',
            'price' => 'required',
            'deal_time' => 'required|date_format:Y-m-d H:i:s|after:today'
        ]);
        $price_discount = filter_var($request->price_discount, FILTER_SANITIZE_NUMBER_INT);
        $price = filter_var($request->price, FILTER_SANITIZE_NUMBER_INT);

        if ($price_discount >= $price) {
            return Redirect::to('/ad/deal')->with('message', 'Giá deal không đúng!');
        }
        $product = Product::findOrFail($request->product_id);
        $product->product_price_discount = $price_discount;
        $product->save();

        $deal = DealProduct::findOrFail($id);
        $deal->product_id = $request->product_id;
        $deal->deal_desc = $request->deal_desc;
        $deal->deal_time = $request->deal_time;
        $deal->save();
        return Redirect::to('/ad/deal')->with('message', 'Sửa deal thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deal = DealProduct::findOrFail($id);
        $product = Product::findOrFail($deal->product_id);
        $product->product_price_discount = $product->product_price;
        $product->save();
        $deal->delete();
        return Redirect::to('/ad/deal')->with('message', 'Xoá deal thành công!');
    }
}
