<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class BrandController extends Controller
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

    public function add_brand()
    {
        $this->AuthLogin();
        return view('admin.brand.add_brand');
    }

    public function save_brand(Request $request)
    {
        $this->AuthLogin();
        $data = $request->all();
        $brand = new Brand();
        $brand->brand_name = $data['brand_name'];
        $brand->brand_desc = $data['brand_desc'];
        $brand->brand_status = $data['brand_status'];
        $brand->save();
        return Redirect::to('/list-brand');
    }

    public function list_brand()
    {
        $this->AuthLogin();
        $all_brand = Brand::paginate(25);
        return view('admin.brand.all_brand')->with('all_brand', $all_brand);
    }

    public function list_brand_detail($id)
    {
        $this->AuthLogin();
        $brand = Brand::findOrFail($id);
        Session::flash('message', 'Liệt kê sản phẩm theo thương hiệu: ' . $brand->brand_name);
        $all_product = Product::where('brand_id', $id)->orderBy('product_order', 'asc')->latest()->paginate(10);
        return view('admin.product.all_product')->with('all_product', $all_product);
    }

    public function inactive_brand($id)
    {
        $this->AuthLogin();
        $data = array();
        $data['brand_status'] = 1;
        Brand::where('brand_id', $id)->update($data);
        return Redirect::to('/list-brand');
    }

    public function active_brand($id)
    {
        $this->AuthLogin();
        $data = array();
        $data['brand_status'] = 0;
        Brand::where('brand_id', $id)->update($data);
        return Redirect::to('/list-brand');
    }

    public function delete_brand(Request $request)
    {

        $this->AuthLogin();
        $brand = Brand::findOrFail($request->brand_id);
        if ($brand->product->count() > 0) {
            $data['status'] = false;
            $data['message'] = 'Bạn phải xóa các sản phẩm thuộc thương hiệu này trước!';
        } else {
            $brand->delete();
            $data['status'] = true;
            $data['message'] = 'Xóa thương hiệu thành công!';
        }
        return json_encode($data);
    }

    public function edit_brand($id)
    {
        $this->AuthLogin();
        $brand_edit = Brand::where('brand_id', $id)->first();
        return view('admin.brand.edit_brand')->with('brand_edit', $brand_edit);
    }

    public function save_edit_brand(Request $request, $id)
    {
        $this->AuthLogin();
        $data['brand_id'] = $id;
        $data['brand_name'] = $request->brand_name;
        $data['brand_desc'] = $request->brand_desc;
        $data['brand_status'] = $request->brand_status;
        Brand::where('brand_id', $id)->update($data);
        return Redirect::to('/list-brand');
    }

    public function search_brand(Request $request)
    {
        $this->AuthLogin();
        $keyword =  $request->keyword;
        $all_brand = brand::where('brand_name', 'like', '%' . $keyword . '%')->paginate(6);
        if (count($all_brand) > 0) {
            return view('admin.brand.all_brand')->with('all_brand', $all_brand);
        } else {
            Session::put('message', 'Không tìm thấy kết quả nào');
            return Redirect::to('/list-brand');
        }
    }
}
