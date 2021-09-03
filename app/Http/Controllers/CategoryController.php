<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\View\Components\Recusive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Storage;

class CategoryController extends Controller
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategory($parentId)
    {
        $data = $this->category->orderBy('category_order', 'asc')->get();
        $recusive = new Recusive($data);
        $htmlOption = $recusive->categoryRecusive($parentId);
        return $htmlOption;
    }

    public function AuthLogin()
    {
        $login = Auth::id();
        if ($login) {
            return Redirect::to('/dashboard');
        } else {
            return Redirect::to('/login-auth')->send();
        }
    }

    public function add_category()
    {
        $this->AuthLogin();
        $htmlOption = $this->getCategory($parentId = '');
        $category = Category::where('role', 1)->get();
        return view('admin.category.add_category')->with('htmlOption', $htmlOption)->with(compact('category'));
    }

    public function save_category(Request $request)
    {
        $this->AuthLogin();
        $data = $request->all();
        $category = new Category();
        $category->category_name = $data['category_name'];
        $category->category_desc = $data['category_desc'];
        $category->category_status = $data['category_status'];
        $category->category_parentId = $data['category_parentId'];
        if ($data['category_parentId'] == 0) {
            $category->role = 1;
        }
        $category->category_slug = url_slug($data['category_name']) . "-" . date('dmYHis');
        $get_image = $request->file('category_image');
        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . date('dmYHis')  . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('uploads/category', $new_image);
            $category->category_image_name = $new_image;
            $category->category_image = $this->upload_to_drive($new_image);
        } else {
            $category->category_image = "";
            $category->category_image_name = "";
        }
        $category->save();
        return Redirect::to('/list-category');
    }

    public function list_category()
    {
        $this->AuthLogin();
        $all_category = $this->category->orderBy('category_parentId', 'desc')->orderBy('category_order', 'asc')->paginate(15);
        return view('admin.category.all_category')->with('all_category', $all_category);
    }

    public function list_category_detail($id)
    {
        $this->AuthLogin();
        $cate = Category::findOrFail($id);
        if (!empty($cate->categoryParent)) {
            Session::flash('message', 'Liệt kê sản phẩm theo thương hiệu: ' . $cate->category_name . ' - ' . $cate->categoryParent->category_name);
        } else {
            Session::flash('message', 'Liệt kê sản phẩm theo thương hiệu: ' . $cate->category_name);
        }

        $all_product = Product::join('tbl_category', 'tbl_category.category_id', 'tbl_product.category_id')
            ->select('tbl_product.*', 'tbl_category.category_parentId')
            ->where('category_parentId', $id)
            ->orWhere('tbl_product.category_id', $id)
            ->orderBy('product_order', 'asc')->latest()->paginate(20);
        return view('admin.product.all_product')->with('all_product', $all_product);
    }

    public function inactive_category($id)
    {
        $this->AuthLogin();
        $data = array();
        $data['category_status'] = 1;
        Category::where('category_id', $id)->update($data);
        return Redirect::to('/list-category');
    }

    public function active_category($id)
    {
        $this->AuthLogin();
        $data = array();
        $data['category_status'] = 0;
        Category::where('category_id', $id)->update($data);
        return Redirect::to('/list-category');
    }

    public function delete_category(Request $request)
    {
        $this->AuthLogin();
        $id = $request->category_id;
        $category = $this->category->find($id);
        if (count($category->categoryChildrent) > 0) {
            echo "Bạn phải xoá hết tất cả danh mục con của danh mục này!";
        } else if (count($category->products) > 0) {
            echo "Bạn phải xoá hết tất cả sản phẩm thuộc danh mục này!";
        } else {
            $image_path = $category->category_image_name;
            $this->delete_from_drive($image_path);
            $category->delete();
            echo "true";
        }
    }

    public function edit_category($id)
    {
        $this->AuthLogin();
        $category_edit = $this->category->find($id);
        $htmlOption = $this->getCategory($category_edit->category_parentId);
        $category = Category::where('role', 1)->get();
        return view('admin.category.edit_category')->with('category_edit', $category_edit)->with('htmlOption', $htmlOption)
            ->with(compact("category"));
    }

    public function save_edit_category(Request $request, $id)
    {
        $this->AuthLogin();
        $data['category_id'] = $id;
        $data['category_name'] = $request->category_name;
        $data['category_desc'] = $request->category_desc;
        $data['category_status'] = $request->category_status;
        $data['category_parentId'] = $request->category_parentId;
        $data['category_slug'] = url_slug($request->category_name) . "-" . date('dmYHis');

        $get_image = $request->file('category_image');
        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . date('dmYHis')  . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('uploads/category', $new_image);
            $data['category_image_name'] = $new_image;
            $data['category_image'] = $this->upload_to_drive($new_image);
            $old_image = $this->category->find($id);
            $this->delete_from_drive($old_image->category_image_name);
        } else {
            $data['category_image'] = "";
            $data['category_image_name'] = "";
        }

        Category::where('category_id', $id)->update($data);
        return Redirect::to('/list-category');
    }

    public function search_category(Request $request)
    {
        $this->AuthLogin();
        $keyword =  $request->keyword;
        $all_category = Category::where('category_name', 'like', '%' . $keyword . '%')->paginate(6);
        if (count($all_category) > 0) {
            return view('admin.category.all_category')->with('all_category', $all_category);
        } else {
            Session::put('message', 'Không tìm thấy kết quả nào');
            return Redirect::to('/list-category');
        }
    }

    public function arrange_category(Request $request)
    {
        $this->AuthLogin();
        $page_id_array = $request->page_id_array;
        foreach ($page_id_array as $key => $value) {
            $category = Category::find($value);
            $category->category_order = $key;
            $category->save();
        }
        echo "true";
    }

    private function upload_to_drive($file_name)
    {
        $file_path = public_path("uploads/category/" . $file_name);
        $file_data = File::get($file_path);
        $contents = collect(Storage::cloud()->listContents('/', true));
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', 'category')
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
