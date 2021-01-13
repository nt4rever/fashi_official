<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ContactPage;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\View\Components\Recusive;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Cart;


class CartController extends Controller
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function show_cart()
    {
        return view('pages.shopping_cart');
    }
    public function add_cart(Request $request)
    {
        try {
            $product_id = $request->product_id;

            $product_qty = 1;
            if (isset($request->product_qty)) {
                $product_qty = $request->product_qty;
            }

            $attribute = "Default";
            $extra_price = 0;
            if (isset($request->attribute)) {
                $attr = ProductAttribute::where('id', $request->attribute)->first();
                if (isset($attr)) {
                    $attribute = $attr->size . '/' . $attr->color;
                    $extra_price = $attr->extra_price;
                }
            }

            $product = $this->product->where('product_id', $product_id)->get();
            foreach ($product as $item) {

                $data['id'] = $item->product_id;
                $cart_search = Cart::search(function ($cartItem, $rowId) use ($item) {
                    return $cartItem->id === $item->product_id;
                });

                $qty_exist = 0;
                if (!$cart_search->isEmpty()) {
                    foreach ($cart_search as $value) {
                        $qty_exist += $value->qty;
                    }
                }


                if ($item->product_quantity < ($product_qty + $qty_exist)) {
                    $re_data['status'] = false;
                    $re_data['message'] = 'Số lượng sản phẩm không đủ';
                    return json_encode($re_data);
                }

                $data['qty'] = $product_qty;
                $data['name'] = $item->product_name;
                $data['price'] = $item->product_price_discount + $extra_price;
                $data['options']['image'] = $item->product_image;
                $data['options']['attribute'] = $attribute;
                $data['options']['slug'] = $item->product_slug;
                $data['weight'] = "123";
                Cart::add($data);
            }
            $str_cart = '';
            $str_cart = $str_cart . '
            <ul class="nav-right">
                            <li class="cart-icon">
                                <a href="' . url('/shopping-cart') . '">
                                    <i class="icon_bag_alt"></i>
                                    <span>' . Cart::content()->count() . '</span>
                                </a>
                                <div class="cart-hover">';
            $str_cart = $str_cart . '
            <div class="select-items">
                                        <table>
                                            <tbody>';
            $cart = Cart::content();
            if (isset($cart)) {
                foreach ($cart as $item) {
                    $str_cart = $str_cart . '<tr>
                        <td class="si-pic"><img src="' . url('uploads/product/' . $item->options->image) . '" alt="" width="100px"></td>
                        <td class="si-text">
                            <div class="product-selected">
                                <p>' . number_format($item->price) . ' đ x ' . $item->qty . '</p>
                                <h6>' . $item->name . '</h6>
                            </div>
                        </td>
                    </tr>';
                }
            }

            $str_cart = $str_cart .   '</tbody>
                    </table>
                    </div>
                    <div class="select-total">
                    <span>total:</span>
                    <h5>' . Cart::total() . ' đ</h5>
                    </div>
                    <div class="select-button">
                    <a href="' . url('/shopping-cart') . '" class="primary-btn view-card">VIEW CARD</a>
                    <a href="' . url('/checkout') . '" class="primary-btn checkout-btn">CHECK OUT</a>
                    </div>';
            $str_cart = $str_cart . '</div>
            </li>
            <li class="cart-price">' . number_format(filter_var(Cart::total(), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) . ' đ</li>
        </ul>';
            $re_data['status'] = true;
            $re_data['message'] = 'Thêm vào giỏ hàng thành công!';
            $re_data['cart'] = $str_cart;
            return json_encode($re_data);
            Session::forget("coupon");
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
    public function cart_destroy()
    {
        Cart::destroy();
        Session::forget("coupon");
        return Redirect::to('/shopping-cart');
    }

    public function update_cart(Request $request)
    {
        try {
            //du lieu vao (input)
            $rowId = $request->rowId;
            $qty = $request->qty;

            //du lieu tra ve
            $return_data = array();

            //get this product in cart
            $this_product = Cart::get($rowId);

            // search so luon san pham ton tai trong gio hang
            $cart_search = Cart::search(function ($cartItem, $rowId) use ($this_product) {
                return $cartItem->id === $this_product->id;
            });
            $qty_exist = 0;
            if (!$cart_search->isEmpty()) {
                foreach ($cart_search as $value) {
                    $qty_exist += $value->qty;
                }
            }

            //kiem tra so luong hang trong CSDL
            $pro = Product::findOrFail($this_product->id);
            if ($pro->product_quantity < (-$this_product->qty + $qty + $qty_exist)) {
                $return_data['status'] = false;
                $return_data['message'] = 'Số lượng sản phẩm không đủ';
                return json_encode($return_data);
            }

            Cart::update($rowId, $qty);


            $return_data['status'] = true;
            $return_data['message'] = 'Change quantity success!';
            //subtotal and total
            $return_data['sub_total'] = '<li class="subtotal">Subtotal <span>' . Cart::subtotal() . ' đ</span></li>
            <li class="cart-total">Total <span>' . Cart::total() . ' đ</span></li>';


            //cart mini
            $str = '<ul class="nav-right">
                        <li class="cart-icon">
                        <a href="' . url('/shopping-cart') . '">
                                <i class="icon_bag_alt"></i>
                                <span>' . Cart::content()->count() . '</span>
                        </a>
                        <div class="cart-hover">
                        <div class="select-items">
                                                        <table>
                                                            <tbody>';
            $cart = Cart::content();
            if (isset($cart)) {
                foreach ($cart as $item) {
                    $str = $str . '<tr>
                                        <td class="si-pic"><img src="' . url('uploads/product/' . $item->options->image) . '" alt="" width="100px"></td>
                                        <td class="si-text">
                                            <div class="product-selected">
                                                <p>' . number_format($item->price) . ' đ x ' . $item->qty . '</p>
                                                <h6>' . $item->name . '</h6>
                                            </div>
                                        </td>
                                    </tr>';
                }
            }

            $str = $str .  '</tbody>
                                    </table>
                                    </div>
                                    <div class="select-total">
                                    <span>total:</span>
                                    <h5>' . Cart::total() . ' đ</h5>
                                    </div>
                                    <div class="select-button">
                                    <a href="' . url('/shopping-cart') . '" class="primary-btn view-card">VIEW CARD</a>
                                    <a href="' . url('/checkout') . '" class="primary-btn checkout-btn">CHECK OUT</a>
                                    </div>
                                </div>
                            </li>
                            <li class="cart-price">' . number_format(filter_var(Cart::total(), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) . ' đ</li>
                        </ul>';
            $return_data['cart_mini'] = $str;
            Session::forget("coupon");
            return json_encode($return_data);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function delete_cart(Request $request)
    {
        try {
            $rowId = $request->rowid;

            Cart::update($rowId, 0);
            $return_data = array();

            //subtotal and total
            $return_data['sub_total'] = '<li class="subtotal">Subtotal <span>' . Cart::subtotal() . ' đ</span></li>
                                            <li class="cart-total">Total <span>' . Cart::total() . ' đ</span></li>';


            //cart mini
            $str = '<ul class="nav-right">
                        <li class="cart-icon">
                        <a href="' . url('/shopping-cart') . '">
                                <i class="icon_bag_alt"></i>
                                <span>' . Cart::content()->count() . '</span>
                        </a>
                        <div class="cart-hover">
                        <div class="select-items">
                                                        <table>
                                                            <tbody>';
            $cart = Cart::content();
            if (isset($cart)) {
                foreach ($cart as $item) {
                    $str = $str . '<tr>
                                        <td class="si-pic"><img src="' . url('uploads/product/' . $item->options->image) . '" alt="" width="100px"></td>
                                        <td class="si-text">
                                            <div class="product-selected">
                                                <p>' . number_format($item->price) . ' đ x ' . $item->qty . '</p>
                                                <h6>' . $item->name . '</h6>
                                            </div>
                                        </td>
                                    </tr>';
                }
            }

            $str = $str .  '</tbody>
                                    </table>
                                    </div>
                                    <div class="select-total">
                                    <span>total:</span>
                                    <h5>' . Cart::total() . ' đ</h5>
                                    </div>
                                    <div class="select-button">
                                    <a href="' . url('/shopping-cart') . '" class="primary-btn view-card">VIEW CARD</a>
                                    <a href="' . url('/checkout') . '" class="primary-btn checkout-btn">CHECK OUT</a>
                                    </div>
                                </div>
                            </li>
                            <li class="cart-price">' . number_format(filter_var(Cart::total(), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) . ' đ</li>
                        </ul>';
            $return_data['cart_mini'] = $str;
            Session::forget("coupon");
            echo json_encode($return_data);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function check_coupon(Request $request)
    {
        if (Cart::count() > 0) {
            $coupon = Coupon::where('coupon_code', $request->coupon)->where('coupon_time', '>', 0)
                ->where('coupon_status', 0)->where('coupon_start', '<', Carbon::now())
                ->where('coupon_end', '>', Carbon::now())->first();
            if ($coupon) {
                if ($coupon->count() > 0) {
                    $coupon_session = Session::get('coupon');
                    if ($coupon_session) {
                        $is_avaiable = 0;
                        if ($is_avaiable == 0) {
                            $cou[] = array(
                                'coupon_name' => $coupon->coupon_name,
                                'coupon_code' => $coupon->coupon_code,
                                'coupon_condition' => $coupon->coupon_condition,
                                'coupon_number' => $coupon->coupon_number,
                            );
                            Session::put('coupon', $cou);
                        }
                    } else {
                        $cou[] = array(
                            'coupon_name' => $coupon->coupon_name,
                            'coupon_code' => $coupon->coupon_code,
                            'coupon_condition' => $coupon->coupon_condition,
                            'coupon_number' => $coupon->coupon_number,
                        );
                        Session::put('coupon', $cou);
                    }
                    Session::save();
                    return redirect()->back();
                }
            } else {
                Session::flash('message', 'Add coupon Fail!');
                return redirect()->back()->with('error', 'Add coupon Fail!');
            }
        }
        return redirect()->back()->with('error', 'Add coupon Fail!');
    }

    public function cancel_coupon()
    {
        Session::forget("coupon");
        return redirect()->back()->with('error', 'Cancel use coupon!');
    }
}
