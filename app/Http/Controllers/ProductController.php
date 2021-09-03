<?php

namespace App\Http\Controllers;

use App\Exports\ExcelExports;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\View\Components\Recusive;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Excel;
use Storage;

class ProductController extends Controller
{
    private $category;
    private $product;
    private $brand;

    public function __construct(Category $category, Product $product, Brand $brand)
    {
        $this->category = $category;
        $this->product = $product;
        $this->brand = $brand;
    }

    public function AuthLogin()
    {
        $login = Auth::id();
        if ($login) {
            return Redirect::to('/dashboard');
        } else {
            return Redirect::to('/admin')->send();
        }
    }

    public function getCategory($parentId)
    {
        $data = $this->category->where('category_status', 0)->get();
        $recusive = new Recusive($data);
        $htmlOption = $recusive->categoryRecusive($parentId);
        return $htmlOption;
    }

    public function list_product()
    {
        $this->AuthLogin();
        $view = 9;
        if (!empty($_GET['view'])) {
            $view = $_GET['view'];
        }
        $all_product = $this->product->with(['category', 'brand'])->orderBy('product_order', 'asc')->latest()->paginate($view)->appends(request()->query());

        return view('admin.product.all_product')->with('all_product', $all_product);
    }

    public function add_product()
    {
        $this->AuthLogin();
        $brand = DB::table('tbl_brand')->where('brand_status', 0)->get();
        $htmlOption = $this->getCategory($parentId = '');
        return view('admin.product.add_product')->with('category_option', $htmlOption)->with('brand', $brand);
    }

    public function save_product(Request $request)
    {
        $this->AuthLogin();
        try {
            DB::beginTransaction();
            $data = array();
            $data['product_name'] = $request->product_name;
            $data['product_desc'] = $request->product_desc;
            $data['category_id'] = $request->category_id;
            $data['brand_id'] = $request->brand_id;
            $data['product_price'] = filter_var($request->product_price, FILTER_SANITIZE_NUMBER_INT);
            if ($request->product_price_discount == "" || $request->product_price_discount == 0) {
                $data['product_price_discount'] = filter_var($request->product_price, FILTER_SANITIZE_NUMBER_INT);
            } else {
                $data['product_price_discount'] = filter_var($request->product_price_discount, FILTER_SANITIZE_NUMBER_INT);
            }

            if ($data['product_price'] < $data['product_price_discount']) {
                Session::flash('message', 'Sai định dạng giá ở mục sửa đổi sản phẩm');
                return Redirect::to('/list-product');
            }

            $data['product_quantity'] = $request->product_quantity;
            $data['product_content'] = $request->product_content;
            $data['product_status'] = $request->product_status;
            $data['product_slug'] = url_slug($request->product_name) . "-" . date('dmYHis');
            $data['product_tag'] = $request->product_tag;

            $get_image = $request->file('product_image');
            if ($get_image) {
                $get_name_image = $get_image->getClientOriginalName();
                $name_image = current(explode('.', $get_name_image));
                $new_image = $name_image . date('dmYHis')  . '.' . $get_image->getClientOriginalExtension();
                $get_image->move('uploads/product', $new_image);
                $data['product_image_name'] = $new_image;
                $data['product_image'] = $this->upload_to_drive($new_image);
            } else {
                $data['product_image'] = '';
                $data['product_image_name'] = '';
            }

            $id = $this->product->create($data)->product_id;


            DB::commit();
            return Redirect::to('/list-product');
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function change_status_product(Request $request)
    {
        $this->AuthLogin();
        try {
            $id = $request->product_id;
            $status = $request->product_status;

            if ($status == 0) {
                $status = 1;
            } else {
                $status = 0;
            }
            $data = array();
            $data['product_status'] = $status;
            $this->product::where('product_id', $id)->update($data);
            echo true;
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }


    public function delete_product(Request $request)
    {
        $this->AuthLogin();

        try {
            $id = $request->product_id;
            $product = $this->product->find($id);
            $image = $product->product_image_name;
            $this->delete_from_drive($image);
            $product->delete();
            echo "true";
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function edit_product($id)
    {
        $this->AuthLogin();
        $product = $this->product->with(['category', 'brand'])->find($id);
        $brand = $this->brand->all();
        $htmlOption = $this->getCategory($product->category->category_id);
        return view('admin.product.edit_product')->with('product', $product)
            ->with('htmlOption', $htmlOption)->with('brand', $brand);
    }

    public function save_edit_product(Request $request, $id)
    {
        $this->AuthLogin();
        try {
            DB::beginTransaction();
            $data = array();
            $data['product_name'] = $request->product_name;
            $data['product_desc'] = $request->product_desc;
            $data['category_id'] = $request->category_id;
            $data['brand_id'] = $request->brand_id;
            $data['product_price'] = filter_var($request->product_price, FILTER_SANITIZE_NUMBER_INT);

            if ($request->product_price_discount == "" || $request->product_price_discount == 0) {
                $data['product_price_discount'] = filter_var($request->product_price, FILTER_SANITIZE_NUMBER_INT);
            } else {
                $data['product_price_discount'] = filter_var($request->product_price_discount, FILTER_SANITIZE_NUMBER_INT);
            }

            if ($data['product_price'] < $data['product_price_discount']) {
                Session::flash('message', 'Sai định dạng giá ở mục sửa đổi sản phẩm');
                return Redirect::to('/list-product');
            }
            $data['product_quantity'] = $request->product_quantity;
            $data['product_content'] = $request->product_content;
            $data['product_status'] = $request->product_status;
            // $data['product_slug'] = url_slug($request->product_name) . "-" . date('dmYHis');
            $data['product_tag'] = $request->product_tag;

            $get_image = $request->file('product_image');
            if ($get_image) {
                $get_name_image = $get_image->getClientOriginalName();
                $name_image = current(explode('.', $get_name_image));
                $new_image = $name_image . date('dmYHis')  . '.' . $get_image->getClientOriginalExtension();
                $get_image->move('uploads/product', $new_image);
                $data['product_image_name'] = $new_image;
                $data['product_image'] = $this->upload_to_drive($new_image);

                //delete image
                $product = $this->product->find($id);
                $image = $product->product_image_name;
                $this->delete_from_drive($image);
            }

            $this->product->find($id)->update($data);
            DB::commit();
            return Redirect::to('/list-product');
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function search_product(Request $request)
    {
        $this->AuthLogin();
        $keyword = $request->keyword;
        $all_product = $this->product->with(['category', 'brand'])->where('product_name', 'like', '%' . $keyword . '%')
            ->latest()->paginate(4);
        if (count($all_product) > 0) {
            return view('admin.product.all_product')->with('all_product', $all_product);
        } else {
            Session::put('message', 'Không tìm thấy kết quả nào');
            return Redirect::to('/list-product');
        }
    }

    public function view_product_content(Request $request)
    {
        $this->AuthLogin();
        $id = $request->product_id;
        $pro = $this->product->find($id);
        echo $pro->product_content;
    }

    public function product_attribute($id)
    {
        $this->AuthLogin();
        $attribute = ProductAttribute::where('product_id', $id)->get();
        $product = $this->product->where('product_id', $id)->first();
        return view('admin.product.attribute', compact('attribute', 'product'));
    }

    public function add_product_attribute(Request $request, $id)
    {
        $this->AuthLogin();
        $data = array();
        $data['product_id'] = $id;
        $data['size'] = $request->size;
        $data['color'] = $request->color;
        $data['extra_price'] = $request->extra_price;
        ProductAttribute::create($data);
        return redirect()->back();
    }

    public function delete_product_attribute(Request $request)
    {
        $this->AuthLogin();
        ProductAttribute::where('id', $request->id)->delete();
        echo "true";
    }

    public function export_csv()
    {
        return Excel::download(new ExcelExports, 'product.xlsx');
    }

    public function add_rating(Request $request)
    {
        $customer_id = Session::get('customer_id');
        if ($customer_id) {
            $cus = Rating::where('customer_id', $customer_id)->where('product_id', $request->product_id)->first();
            if ($cus) {
                $cus->rating = $request->index;
                $cus->save();
            } else {
                $rating = new Rating();
                $rating->customer_id = $customer_id;
                $rating->rating = $request->index;
                $rating->product_id = $request->product_id;
                $rating->save();
            }
            echo "true";
        } else {
            echo "Chua dang nhap";
        }
    }

    public function arrange_product(Request $request)
    {
        $this->AuthLogin();
        $page_id_array = $request->page_id_array;
        foreach ($page_id_array as $key => $value) {
            $product = Product::find($value);
            $product->product_order = $key;
            $product->save();
        }
        echo "true";
    }

    private function upload_to_drive($file_name)
    {
        $file_path = public_path("uploads/product/" . $file_name);
        $file_data = File::get($file_path);
        $contents = collect(Storage::cloud()->listContents('/', true));
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', 'product')
            ->first();
        $file_name = $dir['path'] . "/" . $file_name;
        Storage::cloud()->put($file_name, $file_data);
        $url = Storage::cloud()->url($file_name);
        return $url;
    }

    private function delete_from_drive($filename)
    {
        $contents = collect(Storage::cloud()->listContents("/", true));
        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first();
        Storage::cloud()->delete($file['path']);
    }
}
