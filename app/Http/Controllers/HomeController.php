<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\CategoryPost;
use App\Models\Comment;
use App\Models\ContactPage;
use App\Models\DealProduct;
use App\Models\Faq;
use App\Models\HomeSlider;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Rating;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use stdClass;

class HomeController extends Controller
{
    private $product;
    private $brand;
    private $comment;

    public function __construct(Product $product, Brand $brand, Comment $comment)
    {
        $this->product = $product;
        $this->brand = $brand;
        $this->comment = $comment;
    }

    public function getTag($count)
    {
        $product = Product::where('product_status', 0)->where('product_quantity', '>', 0)->get();
        $data  = array();
        foreach ($product as $item) {
            if ($item->product_tag) {
                $arr = explode(',', $item->product_tag);
                foreach ($arr as $value) {
                    array_push($data, $value);
                }
            }
        }
        $data = array_count_values($data);
        arsort($data);
        if ($data == null) {
            return $data;
        }
        if (sizeof($data) <= $count) {
            return $data;
        } else {
            return array_slice($data, '0', $count);
        }
    }

    public function index(Request $request)
    {
        $visitor = DB::table('tbl_visitor')->where('ip', $request->ip())->first();
        if ($visitor) {
            DB::table('tbl_visitor')->where('ip', $request->ip())->update(['date' => Carbon::now()]);
        } else {
            DB::table('tbl_visitor')->insert(['ip' => $request->ip(), 'date' => Carbon::now()]);
        }
        $post_home = Post::join('tbl_category_post', 'tbl_category_post.category_post_id', 'tbl_post.category_post_id')
            ->select('tbl_post.*')
            ->where('post_status', 0)->where('category_post_status', 0)->orderBy('post_views', 'desc')
            ->orderBy('post_id', 'desc')->take(3)->get();
        $home_slider = HomeSlider::all();
        $deal = DealProduct::where('deal_time', '>', Carbon::now())->get();
        return view('pages.home')->with(compact('post_home', 'home_slider', 'deal'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function faq()
    {
        $faq = Faq::all();
        return view('pages.faq')->with('faq', $faq);
    }

    public function shop(Request $request)
    {
        $show_paginate = 9;
        if (Session::get('customer_id')) {
            $get_cookie = Cookie::get(Session::get('customer_id'));
            if ($get_cookie) {
                $show_paginate = $get_cookie;
            }
        }

        if (isset($_GET['show'])) {
            $show_paginate = $_GET['show'];
            if (Session::get('customer_id')) {
                Cookie::queue(Session::get('customer_id'), $show_paginate, 7 * 60 * 24);
            }
        }

        $brand = $this->brand->where('brand_status', 0)->get();
        $product = $this->product->join('tbl_category', 'tbl_category.category_id', 'tbl_product.category_id')
            ->select('*', DB::raw('(CASE 
            WHEN tbl_product.product_quantity = "0" THEN "0" 
            ELSE "1" 
            END) AS quantity'))
            ->where('product_status', 0)->where('category_status', 0);
        //->where('product_quantity', '>', 0)
        if (isset($_GET['sortBy'])) {
            switch ($_GET['sortBy']) {
                case 'price_asc':
                    $product = $product->orderBy('product_price_discount', 'asc');
                    break;
                case 'price_desc':
                    $product = $product->orderBy('product_price_discount', 'desc');
                    break;
                case 'best_seller':
                    $product = $product->orderBy('product_sales_quantity', 'desc');
                    break;
            }
        }

        $product = $product->orderBy('quantity', 'desc')->orderBy('product_order', 'asc')->orderBy('product_id', 'desc')
            ->paginate($show_paginate)->appends(request()->query());
        $tag = $this->getTag(7);
        return view('pages.shop')->with(compact('brand', 'product', 'tag'));
    }

    public function category($slug)
    {
        try {
            $this_cate = Category::where('category_slug', $slug)->firstOrFail();
            $id = $this_cate->category_id;

            $show_paginate = 9;
            if (Session::get('customer_id')) {
                $get_cookie = Cookie::get(Session::get('customer_id'));
                if ($get_cookie) {
                    $show_paginate = $get_cookie;
                }
            }

            if (isset($_GET['show'])) {
                $show_paginate = $_GET['show'];
                if (Session::get('customer_id')) {
                    Cookie::queue(Session::get('customer_id'), $show_paginate, 7 * 60 * 24);
                }
            }

            $brand = $this->brand->where('brand_status', 0)->get();
            $product = $this->product->join('tbl_category', 'tbl_category.category_id', 'tbl_product.category_id')
                ->where('product_status', 0)->where('category_status', 0)
                ->where(function ($query) use ($id) {
                    return $query->where('tbl_product.category_id', $id)->orWhere('category_parentId', $id);
                })->where('product_quantity', '>', 0);
            if (isset($_GET['sortBy'])) {
                switch ($_GET['sortBy']) {
                    case 'price_asc':
                        $product = $product->orderBy('product_price_discount', 'asc');
                        break;
                    case 'price_desc':
                        $product = $product->orderBy('product_price_discount', 'desc');
                        break;
                    case 'best_seller':
                        $product = $product->orderBy('product_sales_quantity', 'desc');
                        break;
                }
            }
            $product = $product->orderBy('product_id', 'desc')->paginate($show_paginate)->appends(request()->query());
            $tag = $this->getTag(7);
            return view('pages.shop')->with(compact('brand', 'product',  'this_cate', 'tag'));
        } catch (\Exception $exception) {
            return abort(404);
        }
    }

    public function product($slug)
    {
        try {
            $brand = $this->brand->where('brand_status', 0)->get();
            $product = $this->product->where('product_slug', $slug)
                ->where('product_status', 0)->first();
            $comment = $this->comment->where('product_id', $product->product_id)
                ->where('reply_id', 0)->orderBy('comment_id', 'desc')->limit(15)->get();
            $related_products = $this->product->where('category_id', $product->category_id)
                ->whereNotIn('product_slug', [$slug])
                ->where('product_status', 0)->where('product_quantity', '>', 0)->latest()->limit(4)->get();
            if (isset($product)) {
                $customer_id = Session::get('customer_id');
                if ($customer_id) {
                    $rating = Rating::where('product_id', $product->product_id)->where('customer_id', $customer_id)->first();
                    if ($rating) {
                        $rating = round($rating->rating);
                    } else {
                        $rating = 0;
                    }
                } else {
                    $rating = 0;
                }
                $tag = $this->getTag(7);
                $product->product_views++;
                $product->save();
                return view('pages.product.product_detail')
                    ->with('related_products', $related_products)->with(compact('brand', 'product', 'comment', 'rating', 'tag'));
            }
            return abort(404);
        } catch (\Exception $exception) {
            return abort(404);
            // echo $exception->getMessage();
        }
    }

    public function search()
    {
        if (isset($_GET['category']) && isset($_GET['keyword'])) {
            $category_id = $_GET['category'];
            $keyword = $_GET['keyword'];
            $show_paginate = 9;
            if (Session::get('customer_id')) {
                $get_cookie = Cookie::get(Session::get('customer_id'));
                if ($get_cookie) {
                    $show_paginate = $get_cookie;
                }
            }

            if (isset($_GET['show'])) {
                $show_paginate = $_GET['show'];
                if (Session::get('customer_id')) {
                    Cookie::queue(Session::get('customer_id'), $show_paginate, 7 * 60 * 24);
                }
            }
            $brand = $this->brand->where('brand_status', 0)->get();

            if ($category_id == 0) {
                $product = $this->product->join('tbl_category', 'tbl_category.category_id', 'tbl_product.category_id')
                    ->where('product_status', 0)->where('category_status', 0)
                    ->search($keyword);
                //->where('product_quantity', '>', 0)
                if (isset($_GET['sortBy'])) {
                    switch ($_GET['sortBy']) {
                        case 'price_asc':
                            $product = $product->orderBy('product_price_discount', 'asc');
                            break;
                        case 'price_desc':
                            $product = $product->orderBy('product_price_discount', 'desc');
                            break;
                        case 'best_seller':
                            $product = $product->orderBy('product_sales_quantity', 'desc');
                            break;
                    }
                }
                $product = $product->orderBy('product_id', 'desc')->paginate($show_paginate)->appends(request()->query());
                $tag = $this->getTag(7);
                return view('pages.shop')->with(compact('tag', 'product', 'brand'));
            } else {
                $product = $this->product->join('tbl_category', 'tbl_category.category_id', 'tbl_product.category_id')
                    ->where('product_status', 0)->where('category_status', 0)
                    ->where(function ($query) use ($category_id) {
                        return $query->where('tbl_product.category_id', $category_id)->orWhere('category_parentId', $category_id);
                    })->search($keyword);
                //->where('product_quantity', '>', 0)
                if (isset($_GET['sortBy'])) {
                    switch ($_GET['sortBy']) {
                        case 'price_asc':
                            $product = $product->orderBy('product_price_discount', 'asc');
                            break;
                        case 'price_desc':
                            $product = $product->orderBy('product_price_discount', 'desc');
                            break;
                        case 'best_seller':
                            $product = $product->orderBy('product_sales_quantity', 'desc');
                            break;
                    }
                }
                $product = $product->orderBy('product_id', 'desc')->paginate($show_paginate)->appends(request()->query());
                $tag = $this->getTag(7);
                return view('pages.shop')->with(compact('tag', 'product', 'brand'));
            }
        } else {
            return Redirect::to('/shop');
        }
    }

    public function filter_range()
    {
        if (isset($_GET['min_price']) && isset($_GET['max_price'])) {

            $show_paginate = 9;
            if (Session::get('customer_id')) {
                $get_cookie = Cookie::get(Session::get('customer_id'));
                if ($get_cookie) {
                    $show_paginate = $get_cookie;
                }
            }

            if (isset($_GET['show'])) {
                $show_paginate = $_GET['show'];
                if (Session::get('customer_id')) {
                    Cookie::queue(Session::get('customer_id'), $show_paginate, 7 * 60 * 24);
                }
            }

            $min = $_GET['min_price'] * 1000;
            $max = $_GET['max_price'] * 1000;
            if (isset($_GET['brand'])) {
                $filter_brand = $_GET['brand'];
            }
            $brand = $this->brand->where('brand_status', 0)->get();

            if (isset($filter_brand) && !empty($filter_brand)) {
                $product = $this->product->join('tbl_category', 'tbl_category.category_id', 'tbl_product.category_id')
                    ->where('product_status', 0)->where('category_status', 0)
                    // ->where('product_quantity', '>', 0)
                    ->whereIn('brand_id', array_values($filter_brand))
                    ->whereBetween('product_price_discount', [$min, $max]);
            } else {
                $product = $this->product->join('tbl_category', 'tbl_category.category_id', 'tbl_product.category_id')
                    ->where('product_status', 0)->where('category_status', 0)
                    // ->where('product_quantity', '>', 0)
                    ->whereBetween('product_price_discount', [$min, $max]);
            }

            if (isset($_GET['sortBy'])) {
                switch ($_GET['sortBy']) {
                    case 'price_asc':
                        $product = $product->orderBy('product_price_discount', 'asc');
                        break;
                    case 'price_desc':
                        $product = $product->orderBy('product_price_discount', 'desc');
                        break;
                    case 'best_seller':
                        $product = $product->orderBy('product_sales_quantity', 'desc');
                        break;
                }
            }
            $product = $product->orderBy('product_id', 'desc')->paginate($show_paginate)->appends(request()->query());
            $tag = $this->getTag(7);
            return view('pages.shop')->with(compact('brand', 'product', 'tag'));;
        } else {
            return Redirect::to('/shop');
        }
    }

    public function list_order()
    {
        $customer_id = Session::get('customer_id');
        $cart = Order::where('customer_id', $customer_id)->latest()->paginate(10);
        return view('pages.checkout.list_order')->with('cart', $cart);
    }

    public function quick_view(Request $request)
    {
        try {
            $product_id = $request->product_id;
            $item = $this->product->where('product_id', $product_id)->first();
            echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-pic-zoom">
                        <img class="product-big-img"
                            src="' . url('uploads/product/' . $item->product_image) . '" />
                        <div class="zoom-icon">
                            <i class="fa fa-search-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-details">
                        <div class="pd-title">
                            <span>' . $item->category->category_name . '</span>
                            <h3>' . $item->product_name . '</h3>
                            
                        </div>
                        <div class="pd-rating">';
            if ($item->rating->avg('rating')) {
                $rating = round($item->rating->avg('rating'));
                for ($i = 1; $i <= $rating; $i++) {
                    echo '<i class="fa fa-star"></i> ';
                }
                for ($i = 1; $i <= 5 - $rating; $i++) {
                    echo '<i class="fa fa-star-o"></i> ';
                }
                echo '<span>(' . $item->rating->count() . ')</span>';
            }
            echo '</div>
                        <div class="pd-desc">
                            <p>' . $item->product_desc . '</p>
                            <h4>';
            if ($item->product_price_discount == $item->product_price) {
                echo number_format($item->product_price_discount) . ' đ';
            } else {
                echo number_format($item->product_price_discount) . ' đ';
                echo '<span>' . number_format($item->product_price) . ' đ</span>';
            }

            echo                '</h4>
                        </div>';
            if ($item->product_quantity > 0) {
                echo '<div class="quantity">
                    <div class="pro-qty">
                        <input type="text" value="1" class="product_qty"
                            name="">
                    </div>
                    <a href="#" class="primary-btn pd-add-cart add_to_cart add-to-cart-icon"
                        data-product_id="' . $item->product_id . '">Add
                        To Cart</a>
                </div>';
            } else {
                echo '<span class="btn btn-outline-secondary font-weight-bold" style="cursor: default">SOLD OUT</span>';
            }

            echo '<ul class="pd-tags">
                            <li><span>CATEGORIES</span>: ' . $item->category->category_name . '</li>
                            <li><span>BRAND</span>: ' . $item->brand->brand_name . '</li>
                            <li><span>QUANTITY</span>: ' . $item->product_quantity . '</li>
                            <li><span>Sales quantity</span>: ' . $item->product_sales_quantity . '</li>
                        </ul>
                        <div class="pd-share">
                            <div class="p-code">ID : ' . $item->product_id . '</div>
                            <div class="pd-social">
                                <a href="#"><i class="ti-facebook"></i></a>
                                <a href="#"><i class="ti-twitter-alt"></i></a>
                                <a href="#"><i class="ti-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ';
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function blog()
    {
        $category_post = CategoryPost::where('category_post_status', 0)->get();
        $all_post = Post::where('post_status', 0)->paginate(6);
        $recent_post = Post::where('post_status', 0)->latest()->take(5)->get();
        return view('pages.post.blog')->with('category_post', $category_post)->with(compact('all_post', 'recent_post'));
    }

    // view in customer
    public function category_post($slug)
    {
        $category_post = CategoryPost::where('category_post_status', 0)->get();
        $id = CategoryPost::where('category_post_slug', $slug)->where('category_post_status', 0)->first();
        if (isset($id)) {
            $all_post = Post::where('category_post_id', $id->category_post_id)->where('post_status', 0)->paginate(6);
        } else {
            $all_post = new stdClass();
        }
        $recent_post = Post::where('post_status', 0)->latest()->take(5)->get();
        return view('pages.post.blog')->with('category_post', $category_post)->with(compact('all_post', 'recent_post'));
    }

    public function view_post($slug)
    {
        $id = Post::where('post_slug', $slug)->first();
        if (isset($id)) {
            $post = Post::join('tbl_category_post', 'tbl_category_post.category_post_id', 'tbl_post.category_post_id')
                ->select('tbl_post.*', 'tbl_category_post.category_post_name')->where('post_id', $id->post_id)
                ->where('category_post_status', 0)->where('post_status', 0)->first();
            $post->post_views++;
            $post->save();
        } else {
            $post = null;
        }
        $contact = ContactPage::first();
        return view('pages.post.blog_detail')->with(compact('post'));
    }

    public function tag($tag)
    {
        try {
            $show_paginate = 9;
            if (isset($_GET['show'])) {
                $show_paginate = $_GET['show'];
            }
            $brand = $this->brand->where('brand_status', 0)->get();
            $tag = str_replace("-", " ", $tag);
            $product = $this->product->join('tbl_category', 'tbl_category.category_id', 'tbl_product.category_id')
                ->where(function ($query) use ($tag) {
                    return $query->where('product_tag', 'LIKE', '%' . $tag . '%')->orWhere('product_name', 'LIKE', '%' . $tag . '%')
                        ->orWhere('product_slug', 'LIKE', '%' . $tag . '%');
                })
                ->where('product_tag', 'LIKE', '%' . $tag . '%')->orWhere('product_name', 'LIKE', '%' . $tag . '%')
                ->where('product_status', 0)->where('category_status', 0)
                ->where('product_quantity', '>', 0);
            if (isset($_GET['sortBy'])) {
                switch ($_GET['sortBy']) {
                    case 'price_asc':
                        $product = $product->orderBy('product_price_discount', 'asc');
                        break;
                    case 'price_desc':
                        $product = $product->orderBy('product_price_discount', 'desc');
                        break;
                    case 'best_seller':
                        $product = $product->orderBy('product_sales_quantity', 'desc');
                        break;
                }
            }
            $product = $product->orderBy('product_id', 'desc')->paginate($show_paginate)->appends(request()->query());
            $tag = $this->getTag(7);
            return view('pages.shop')
                ->with(compact('brand',  'product', 'tag'));
        } catch (\Exception $exception) {
            echo "404 - Error!";
        }
    }
}
