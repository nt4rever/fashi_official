<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryPost;
use App\Models\Comment;
use App\Models\CommentPost;
use App\Models\ContactPage;
use App\Models\Post;
use App\Models\Product;
use App\View\Components\Recusive;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Storage;

class PostController extends Controller
{
    private $category;
    private $product;
    private $brand;
    private $comment;

    public function __construct(Category $category, Product $product, Brand $brand, Comment $comment)
    {
        $this->category = $category;
        $this->product = $product;
        $this->brand = $brand;
        $this->comment = $comment;
    }

    public function getCategory($parentId)
    {
        //nav bar
        $data = $this->category->where('category_status', 0)->orderBy('category_order', 'asc')->get();
        $recusive = new Recusive($data);
        $htmlOption = $recusive->categoryRecusiveHome($parentId);
        return $htmlOption;
    }

    public function getCategoryOption($parentId)
    {
        //form search product
        $data = $this->category->where('category_status', 0)->orderBy('category_order', 'asc')->get();
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



    public function add_post()
    {
        $this->AuthLogin();
        $category_post = CategoryPost::orderBy('category_post_id', 'desc')->get();
        return view('admin.post.add_post', compact('category_post'));
    }

    public function save_post(Request $request)
    {
        $this->AuthLogin();
        $this->validate($request, [
            'post_title' => 'required',
            'post_desc' => 'required',
            'post_content' => 'required',
            'post_meta_desc' => 'required',
            'post_meta_keyword' => 'required',
            'post_status' => 'required',
            'category_post_id' => 'required',
            'post_slug' => 'required',
            'post_image' => 'required',
        ]);

        $post = new Post();
        $post->post_title = $request->post_title;
        $post->post_desc = $request->post_desc;
        $post->post_content = $request->post_content;
        $post->post_meta_desc = $request->post_meta_desc;
        $post->post_meta_keyword = $request->post_meta_keyword;
        $post->post_status = $request->post_status;
        $post->category_post_id = $request->category_post_id;
        $post->post_slug = $request->post_slug . "-" . date('dmYHis');
        $post->post_status = $request->post_status;

        $get_image = $request->file('post_image');
        if ($get_image) {
            $new_image = $request->post_slug . date('dmYHis')  . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('uploads/post', $new_image);
            $post->post_image_name = $new_image;
            $post->post_image = $this->upload_to_drive($new_image);
        } else {
            $post->post_image = '';
        }

        $post->save();
        Session::flash('message', 'Thêm thành công!');
        return Redirect::to('/all-post');
    }

    public function post()
    {
        $this->AuthLogin();
        $all_post = Post::orderBy('post_id', 'desc')->paginate(9);
        return view('admin.post.all_post', compact('all_post'));
    }

    public function edit_post($id)
    {
        $this->AuthLogin();
        $post = Post::find($id);
        $category_post = CategoryPost::orderBy('category_post_id', 'desc')->get();
        return view('admin.post.edit_post', compact('post', 'category_post'));
    }

    public function save_edit_post(Request $request, $id)
    {
        $this->AuthLogin();
        $this->validate($request, [
            'post_title' => 'required',
            'post_desc' => 'required',
            'post_content' => 'required',
            'post_meta_desc' => 'required',
            'post_meta_keyword' => 'required',
            'post_status' => 'required',
            'category_post_id' => 'required',
            'post_slug' => 'required',
        ]);

        $post = Post::find($id);
        $post->post_title = $request->post_title;
        $post->post_desc = $request->post_desc;
        $post->post_content = $request->post_content;
        $post->post_meta_desc = $request->post_meta_desc;
        $post->post_meta_keyword = $request->post_meta_keyword;
        $post->post_status = $request->post_status;
        $post->category_post_id = $request->category_post_id;
        if ($post->post_slug != $request->post_slug) {
            $post->post_slug = $request->post_slug . "-" . date('dmYHis');
        }
        $post->post_status = $request->post_status;
        $img = $post->post_image_name;
        $get_image = $request->file('post_image');
        if ($get_image) {
            $new_image = $request->post_slug . date('dmYHis')  . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('uploads/post', $new_image);
            $post->post_image_name = $new_image;
            $post->post_image = $this->upload_to_drive($new_image);
            $this->delete_from_drive($img);
            // $image_path = "uploads/post/" . $img;
            // if (File::exists($image_path)) {
            //     File::delete($image_path);
            // }
        }
        $post->save();
        Session::flash('message', 'Sửa thành công!');
        return Redirect::to('/all-post');
    }

    public function delete_post($id)
    {
        $post = Post::find($id);
        $img = $post->post_image_name;
        $this->delete_from_drive($img);
        // $image_path = "uploads/post/" . $img;
        // if (File::exists($image_path)) {
        //     File::delete($image_path);
        // }
        $post->delete();
        Session::flash('message', 'Xoá thành công!');
        return Redirect::to('/all-post');
    }

    public function add_post_comment(Request $request)
    {
        $new = new CommentPost();
        $new->post_id = $request->post_id;
        $new->customer_id = $request->customer_id;
        $new->time = $request->comment_time;
        $new->content = $request->content;
        $new->save();
        $all = CommentPost::where('post_id', $request->post_id)->get();
        foreach ($all as $item) {
            echo '
            <div class="posted-by  mb-2">
                            <div class="pb-pic">
                                <img src="' . $item->customer->customer_image . '" alt="" width="80">
                            </div>
                            <div class="pb-text">
                                <h5><span style="font-weight: 600">' . $item->customer->customer_name . '</span></h5>
                                <span class="text-muted small">' . $item->time . '</span>

                                <p>' . $item->content . '</p>
                            </div>

                        </div>
            ';
        }
    }

    public function all_post_comment()
    {
        $comment = CommentPost::orderBy('id', 'desc')->paginate(15);
        return view('admin.post.comment', compact('comment'));
    }

    public function delete_post_comment(Request $request)
    {
        $comment = CommentPost::findOrFail($request->id);
        $comment->delete();
        return true;
    }

    public function search()
    {
        try {
            $keyword = $_GET['keyword'];
            $all_post = Post::where('post_status', 0)->where('post_title', 'LIKE', '%' . $keyword . '%')->paginate(6);
            $category_post = CategoryPost::where('category_post_status', 0)->get();
            $recent_post = Post::where('post_status', 0)->latest()->take(5)->get();
            return view('pages.post.blog')->with('category_post', $category_post)->with(compact('all_post', 'recent_post'));
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    private function upload_to_drive($file_name)
    {
        $file_path = public_path("uploads/post/" . $file_name);
        $file_data = File::get($file_path);
        $contents = collect(Storage::cloud()->listContents('/', true));
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', 'post')
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
