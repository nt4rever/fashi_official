<?php

namespace App\Http\Controllers;

use App\Models\CategoryPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CategoryPostController extends Controller
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

    public function add_post()
    {
        $this->AuthLogin();
        return view('admin.post.add_category_post');
    }

    public function index()
    {
        $this->AuthLogin();
        $category_post = CategoryPost::paginate(9);
        return view('admin.post.all_category_post', compact('category_post'));
    }

    public function save_post(Request $request)
    {
        $this->AuthLogin();
        $this->validate($request, [
            'category_post_name' => 'required|max:255',
            'category_post_desc' => 'required',
            'category_post_status' => 'required',
            'category_post_slug' => 'required|max:255',
        ]);
        $data['category_post_name']  = $request->category_post_name;
        $data['category_post_desc']  = $request->category_post_desc;
        $data['category_post_status']  = $request->category_post_status;
        $data['category_post_slug']  = $request->category_post_slug . "-" . date('dmYHis');
        CategoryPost::insert($data);
        Session::flash('message', 'Thêm danh mục tin tức, bài viết thành công!');
        return Redirect::to('/category-post');
    }

    public function delete_post($id)
    {
        $this->AuthLogin();
        $category_post = CategoryPost::find($id);
        $category_post->delete();
        Session::flash('message', 'Xoá thành công!');
        return redirect()->back();
    }

    public function edit_post($id)
    {
        $this->AuthLogin();
        $category_post = CategoryPost::find($id);
        return view('admin.post.edit_category_post', compact('category_post'));
    }

    public function save_edit_post(Request $request, $id)
    {
        $this->AuthLogin();
        $category_post = CategoryPost::find($id);
        $category_post->category_post_name = $request->category_post_name;
        $category_post->category_post_desc = $request->category_post_desc;
        $category_post->category_post_status = $request->category_post_status;
        $category_post->category_post_slug = $request->category_post_slug;
        $category_post->save();
        Session::flash('message', 'Sửa thành công!');
        return Redirect::to('/category-post');
    }

    public function change_status_post($id)
    {
        $this->AuthLogin();
        $category_post = CategoryPost::find($id);
        $category_post->category_post_status = abs($category_post->category_post_status - 1);
        $category_post->save();
        Session::flash('message', 'Thay đổi trạng thái thành công!');
        return Redirect::to('/category-post');
    }
}
