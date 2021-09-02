<?php

namespace App\Http\Controllers;

use App\Jobs\SendCheckoutMail;
use App\Jobs\SendConfirmOrderMail;
use App\Mail\CheckoutMail;
use App\Models\Category;
use App\Models\ContactPage;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\Statistic;
use App\Rules\Captcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\View\Components\Recusive;
use Carbon\Carbon;
use Cart;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use PDF;
use stdClass;

class CheckoutController extends Controller
{
    // private $customer;
    // private $category;

    // public function __construct(Customer $customer, Category $category)
    // {
    //     $this->customer = $customer;
    //     $this->category = $category;
    // }


    public function AuthLogin()
    {
        $login = Auth::id();
        if ($login) {
            return Redirect::to('/dashboard');
        } else {
            return Redirect::to('/login-auth')->send();
        }
    }

    public function checkout()
    {
        $session_customer = Session::get('customer_name');

        $cart = Cart::content();
        if ($cart->count() == 0) {
            return Redirect::to('/home');
        }

        if ($session_customer) {
            // $thanhpho = DB::table('tbl_tinhthanhpho')->get();
            $client = new Client();
            $response = $client->get('https://provinces.open-api.vn/api/?depth=1');
            $thanhpho = json_decode($response->getBody());
            return view('pages.checkout.checkout', compact('thanhpho'));
        } else {
            // Session::put('history', 'cart');
            return Redirect::to('/login-customer');
        }
    }

    public function place_order(Request $request)
    {
        //validate data
        $session_customer = Session::get('customer_name');
        if (!$session_customer) {
            Session::put('history', 'cart');
            return Redirect::to('/login-customer');
        }

        $this->validation($request);

        $cart = Cart::content();
        if ($cart->count() == 0) {
            return Redirect::to('/home');
        }
        //end validate


        //check quantity product enough
        $quantity_restore = array();
        // foreach ($cart as $item) {
        //     $pro_r = Product::find($item->id);
        //     array_push($quantity_restore, array(
        //         'id' => $pro_r->product_id,
        //         'qty' => $pro_r->product_quantity
        //     ));
        // }

        // $qty_same = 0;
        foreach ($cart as $item) {
            // $cart_search = Cart::search(function ($cartItem, $rowId) use ($item) {
            //     return $cartItem->id === $item->id;
            // });
            // $qty_exist = 0;
            // if (!$cart_search->isEmpty()) {
            //     foreach ($cart_search as $value1) {
            //         $qty_exist += $value1->qty;
            //     }
            // }

            // if ($pro->product_quantity >= ($qty_exist - $qty_same)) {
            //     $pro->product_quantity -= $item->qty;
            //     $pro->save();
            //     $qty_same += $item->qty;
            // } else {
            //     foreach ($quantity_restore as $value) {
            //         $pro_restore = Product::find($value['id']);
            //         $pro_restore->product_quantity = $value['qty'];
            //         $pro_restore->save();
            //     }
            //     return redirect()->back()->with('message', 'Số lượng sản phẩm không đủ');
            //     break;
            // }
            $pro = Product::find($item->id);
            if ($pro->product_quantity >= $item->qty) {
                // tru so luong
                $pro->product_quantity -= $item->qty;
                $pro->save();

                //tao backup
                array_push($quantity_restore, array(
                    'id' => $pro->product_id,
                    'qty' => $pro->product_quantity
                ));
            } else {
                //backup
                foreach ($quantity_restore as $value) {
                    $pro_restore = Product::find($value['id']);
                    $pro_restore->product_quantity = $value['qty'];
                    $pro_restore->save();
                }
                return redirect()->back()->with('message', 'Số lượng sản phẩm không đủ');
                break;
            }
        }
        //end check quantity product

        //create shipping
        $shipping = array();
        $shipping['shipping_name'] = $request->fir . " " . $request->last;
        $thanhpho = $request->thanhpho;
        $quanhuyen = $request->quanhuyen;
        $xaphuong = $request->xaphuong;
        $shipping['shipping_address'] = $thanhpho . ' - ' . $quanhuyen . ' - ' . $xaphuong . ' - ' . $request->street;
        $shipping['shipping_phone'] = $request->phone;
        $shipping['shipping_email'] = $request->email;
        $shipping['shipping_note'] = $request->note;
        $shipping_id = Shipping::create($shipping)->shipping_id;
        //end shipping


        //create order : default status = -1(cancel or waiting payment)
        $order = array();
        $order['customer_id'] = Session::get('customer_id');
        $order['shipping_id'] = $shipping_id;
        $order['payment_id'] = $request->payment;
        $order['order_total'] = filter_var(
            Cart::total(),
            FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION
        );
        $order['order_status'] = -1;

        //check coupon
        if ($request->coupon_code) {
            $coupon = Coupon::where('coupon_code', $request->coupon_code)->where('coupon_time', '>', 0)
                ->where('coupon_status', 0)->where('coupon_start', '<', Carbon::now())
                ->where('coupon_end', '>', Carbon::now())->first();
            if ($coupon) {
                $order['coupon_code'] = $request->coupon_code;
                $order['discount'] = $request->discount;
                $coupon->coupon_time = $coupon->coupon_time - 1;
                $coupon->save();
                $total_money = filter_var(
                    Cart::total(),
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                ) - filter_var($request->discount, FILTER_SANITIZE_NUMBER_INT);
                if ($total_money < 0) {
                    $total_money = 0;
                }
            } else {
                $order['coupon_code'] = '';
                $order['discount'] = '';
                $total_money = filter_var(
                    Cart::total(),
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                );
            }
        } else {
            $order['coupon_code'] = '';
            $order['discount'] = '';
            $total_money = filter_var(
                Cart::total(),
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );
        }

        $order_id = Order::create($order)->order_id;
        //end create order

        //order detail
        foreach ($cart as $item) {
            $order_detail = array();
            $order_detail['order_id'] = $order_id;
            $order_detail['product_id'] = $item->id;
            $order_detail['product_name'] = $item->name;
            $order_detail['product_price'] = $item->price;
            $order_detail['product_sales_quantity'] = $item->qty;
            $order_detail['order_attribute'] = $item->options->attribute;
            OrderDetail::create($order_detail);
        }
        //end order detail

        if ($request->payment == 1) {
            $bankcode = $request->bankcode;
            // if ($bankcode == "") {
            //     $bankcode = "NCB";
            // }
            $url = $this->vnpay($bankcode, $order_id,  $total_money);
            return redirect($url);
        } else {
            $order = Order::find($order_id);
            $order->order_status = 0;
            $order->save();

            $email = $request->email;
            $emailJob = new SendCheckoutMail($email, $order, $shipping, $cart);
            $emailJob->delay(Carbon::now()->addSeconds(10));
            dispatch($emailJob);
            Cart::destroy();

            return view('pages.checkout.success', compact('cart', 'shipping', 'order'));
        }
    }

    public function vnpay($bankcode, $order_id, $total)
    {
        $vnp_TmnCode = "W2H8M1CZ"; //Mã website tại VNPAY 
        $vnp_HashSecret = "RDOXWLQLKDWRLJKAQWFDDSUKZYKWWVVF"; //Chuỗi bí mật
        $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "https://fashi.com/GIT_fix/public/checkout-payment";
        $vnp_TxnRef = $order_id;
        $vnp_OrderInfo = 'thanh toan vn pay';
        $vnp_OrderType = 'fashion';
        $vnp_Amount = $total * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = $bankcode;
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            // $vnpSecureHash = md5($vnp_HashSecret . $hashdata);
            $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
            $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
        }
        return $vnp_Url;
    }

    public function validation($request)
    {
        return $this->validate($request, [
            'fir' => 'required|max:100',
            'last' => 'required|max:100',
            'street' => 'required',
            'email'  => 'email|max:100',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'thanhpho' => 'required|min:1',
            'quanhuyen' => 'required|min:1',
            'xaphuong' => 'required|min:1',
            // 'g-recaptcha-response' => new Captcha(),
        ]);
    }

    public function admin_checkout()
    {
        $this->AuthLogin();
        $all_order = Order::latest()->paginate(25);
        return view('admin.checkout.list_order', compact('all_order'));
    }

    public function admin_checkout_pending()
    {
        $this->AuthLogin();
        $all_order = Order::where('order_status', 0)->orderBy('order_id', 'desc')->paginate(25);
        return view('admin.checkout.list_order', compact('all_order'));
    }

    public function admin_checkout_success()
    {
        $this->AuthLogin();
        $all_order = Order::whereIn('order_status', [1, 2])->orderBy('order_id', 'desc')->paginate(25);
        return view('admin.checkout.list_order', compact('all_order'));
    }

    public function admin_checkout_cancel()
    {
        $this->AuthLogin();
        $all_order = Order::where('order_status', -1)->orderBy('order_id', 'desc')->paginate(25);
        return view('admin.checkout.list_order', compact('all_order'));
    }

    public function admin_checkout_detail($id)
    {
        $this->AuthLogin();
        $order = Order::where('order_id', $id)->first();
        $order_detail = OrderDetail::where('order_id', $id)->get();
        return view('admin.checkout.order_detail', compact('order', 'order_detail'));
    }

    public function admin_confirm_order(Request $request)
    {
        $this->AuthLogin();
        try {
            $return_data = array();
            $id = $request->id;
            $order = Order::where('order_id', $id)->first();
            $total_qty = 0;

            //kiem tra da xac nhan
            if ($order->order_status == 1 || $order->order_status == 2) {
                $return_data['message'] = 'Đơn hàng này đã xác nhận - đã giao hàng!';
                $return_data['data'] = '<span class="badge badge-success p-1">Đã xác nhận</span> ';
                return json_encode($return_data);
            }

            //tinh tong so luong san pham
            foreach ($order->order_detail as $item) {
                $pro = Product::find($item->product_id);
                $pro->product_sales_quantity += $item->product_sales_quantity;
                $pro->save();
                $total_qty += $item->product_sales_quantity;
            }

            //tra ve da xac nhan thanh cong
            Order::where('order_id', $id)->update(['order_status' => 1]);
            $return_data['message'] = 'Xác nhận đơn hàng thành công!';
            $return_data['data'] = '<span class="badge badge-success p-1">Đã xác nhận</span> ';

            //them du lieu vao bang thong ke
            $statistical = Statistic::where('order_date', Carbon::now()->toDateString())->first();
            if ($statistical) {
                $statistical->quantity += $total_qty;
                $statistical->total_order++;
                if ($order->discount != "") {
                    if (filter_var($order->order_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) < $order->discount) {
                        $statistical->sales += 0;
                    } else {
                        $statistical->sales += (filter_var($order->order_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) - $order->discount);
                    }
                } else {
                    $statistical->sales += filter_var($order->order_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }
                $statistical->save();
            } else {
                $newstt = new Statistic();
                $newstt->order_date = Carbon::now()->toDateString();
                $newstt->quantity = $total_qty;
                $newstt->total_order = 1;
                if ($order->discount != "") {
                    if (filter_var($order->order_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) < $order->discount) {
                        $newstt->sales += 0;
                    } else {
                        $newstt->sales += (filter_var($order->order_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) - $order->discount);
                    }
                } else {
                    $newstt->sales = filter_var($order->order_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }
                $newstt->save();
            }

            $mail = new SendConfirmOrderMail($order->shipping->shipping_email, $order->shipping, $order->order_id);
            dispatch($mail->delay(Carbon::now()->addSecond(10)));

            echo json_encode($return_data);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function admin_cancel_order(Request $request)
    {
        $this->AuthLogin();
        try {
            $id = $request->id;
            $order = Order::where('order_id', $id)->first();
            if ($order->order_status == 1) {
                $return_data['message'] = 'Đơn hàng này đã xác nhận - đã giao hàng - không thể huỷ!';
                $return_data['data'] = '<span class="badge badge-success p-1">Đã xác nhận</span> ';
                return json_encode($return_data);
            } elseif ($order->order_status == -1) {
                $return_data['message'] = 'Đơn hàng đã bị huỷ trước đó!';
                $return_data['data'] = '<span class="badge badge-danger p-1">Đã huỷ</span> ';
                return json_encode($return_data);
            } elseif ($order->order_status == 2) {
                $return_data['message'] = 'Đơn hàng này đã giao hàng thành công!';
                $return_data['data'] = '<span class="badge badge-info p-1">Đã giao hàng</span> ';
                return json_encode($return_data);
            } else {
                //khoi phuc lai so luong san pham
                foreach ($order->order_detail as $item) {
                    $pro = Product::findOrFail($item->product_id);
                    $pro->product_quantity += $item->product_sales_quantity;
                    $pro->save();
                }

                $order->order_status = -1;
                if ($order->payment_id == 1) {
                    $order->message = 'Hoàn tiền';
                }
                $order->save();
                $return_data['message'] = 'Huỷ đơn hàng thành công!';
                $return_data['data'] = '<span class="badge badge-danger p-1">Đã huỷ</span>';
                return json_encode($return_data);
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function cancel_order(Request $request)
    {
        try {
            $id = $request->id;
            $order = Order::where('order_id', $id)->firstOrFail();
            //kiem tra trang thai
            if ($order->order_status == 1 || $order->order_status == 2) {
                return "Không thể huỷ đơn hàng này!";
            }

            //khoi phuc lai so luong san pham
            foreach ($order->order_detail as $item) {
                $pro = Product::findOrFail($item->product_id);
                $pro->product_quantity += $item->product_sales_quantity;
                $pro->save();
            }
            $order->order_status = -1;
            if ($order->payment_id == 1) {
                $order->message = 'Hoàn tiền';
            }
            $order->save();
            echo '<button class="btn btn-danger" disabled>Đã huỷ</button>';
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function receive_order(Request $request)
    {
        try {
            $id = $request->id;
            $order = Order::where('order_id', $id)->firstOrFail();
            //kiem tra trang thai
            if ($order->order_status == -1 || $order->order_status == 2) {
                return "Thao tác thất bại!";
            }

            //khoi phuc lai so luong san pham
            foreach ($order->order_detail as $item) {
                $pro = Product::findOrFail($item->product_id);
                $pro->product_quantity += $item->product_sales_quantity;
                $pro->save();
            }
            $order->order_status = 2;
            $order->save();
            echo '<button class="btn btn-info" disabled>Đã nhận hàng</button>';
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function print_pdf_checkout($checkout_code)
    {
        $this->AuthLogin();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->print_checkout_convert($checkout_code));
        return $pdf->stream();
    }

    public function print_checkout_convert($checkout_code)
    {
        $order = Order::where('order_id', $checkout_code)->first();
        $order_detail = OrderDetail::where('order_id', $checkout_code)->get();
        $output = '<head>
                    <title>Hoá đơn</title>
                    </head>
                    <style>
                        body {
                            font-family: DejaVu Sans;
                        }
                    </style>
                    <h1 style="text-align:center">Th&ocirc;ng tin đơn h&agrave;ng&nbsp;</h1>

                    <p>Kh&aacute;ch h&agrave;ng: ' . $order->customer->customer_name . '</p>

                    <p>Thời gian đặt h&agrave;ng:&nbsp;' . $order->created_at . '</p>

                    <p>Chi tiết đơn h&agrave;ng:</p>

                    <table border="1" cellpadding="1" cellspacing="1" style="width:700px">
                        <thead>
                            <tr>
                                <th scope="col">STT</th>
                                <th scope="col">T&ecirc;n sản phẩm</th>
                                <th scope="col">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Thuộc t&iacute;nh</th>
                                        </tr>
                                    </thead>
                                </table>
                                </th>
                                <th scope="col">Đơn gi&aacute;</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Th&agrave;nh tiền</th>
                            </tr>
                        </thead>
                        <tbody>';
        $i = 0;
        foreach ($order_detail as $item) {
            $i++;
            $output = $output .
                '<tr>
                                <td>' . $i . '</td>
                                <td>' . $item->product_name . '</td>
                                <td>' . $item->order_attribute . '</td>
                                <td>' . number_format($item->product_price) . ' đ</td>
                                <td>' . $item->product_sales_quantity . '</td>
                                <td>' . number_format($item->product_price * $item->product_sales_quantity) . ' đ</td>
                            </tr>';
        }
        $output = $output . '<tr>
                                <td colspan="5" style="text-align:center">Tổng</td>
                                <td>' . number_format($order->order_total) . ' đ</td>
                            </tr>
                            <tr>
                                <td colspan="5" style="text-align:center">Giảm gi&aacute;</td>
                                <td>';
        if ($order->discount != "") {
            $output = $output . number_format($order->discount) . ' đ';
        }

        $output = $output .             '</td>
                            </tr>
                            <tr>
                                <td colspan="5" rowspan="1" style="text-align:center"><strong>Th&agrave;nh tiền</strong></td>
                                <td>';
        if ($order->discount != "") {
            if (filter_var(
                $order->order_total,
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            ) >= $order->discount) {
                $output = $output . number_format(filter_var(
                    $order->order_total,
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                ) - $order->discount);
            } else {
                $output = $output . '0';
            }
        } else {
            $output = $output . number_format($order->order_total);
        }
        $output = $output . ' đ</td>
                            </tr>
                        </tbody>
                    </table>';
        if ($order->discount != "") {
            $output = $output . '<p><span class="marker">M&atilde; giảm gi&aacute;: ' . $order->coupon_code . '</span></p>';
        }
        if ($order->payment_id == 0) {
            $payment = 'Thanh toán khi nhận hàng';
        } else {
            $payment = 'Thanh toán VNPAY';
        }
        $output = $output . '
                    <p>Phương thức thanh toán: ' . $payment . '</p>
                    <p>Th&ocirc;ng tin vận chuyển:</p>

                    <p>T&ecirc;n người nhận: ' . $order->shipping->shipping_name . '</p>

                    <p>Địa chỉ:&nbsp;' . $order->shipping->shipping_address . '</p>

                    <p>Số điện thoại:&nbsp;' . $order->shipping->shipping_phone . '</p>

                    <p>Email:&nbsp;' . $order->shipping->shipping_email . '</p>

                    <p>C&aacute;m ơn qu&yacute; kh&aacute;ch đ&atilde; sử dụng dịch vụ của ch&uacute;ng t&ocirc;i!</p>

                    <div style="background:#eeeeee; border:1px solid #cccccc; padding:5px 10px; text-align:center">Fashi shop</div>

                    <p>&nbsp;</p> 
        ';
        return $output;
    }

    public function give_message(Request $request)
    {
        $order = Order::find($request->id);
        $order->message = $request->message;
        $order->save();
        echo $request->message;
    }

    public function check_payment()
    {
        $vnp_TmnCode = "W2H8M1CZ"; //Mã website tại VNPAY 
        $vnp_HashSecret = "RDOXWLQLKDWRLJKAQWFDDSUKZYKWWVVF"; //Chuỗi bí mật
        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHashType']);
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . $key . "=" . $value;
            } else {
                $hashData = $hashData . $key . "=" . $value;
                $i = 1;
            }
        }

        //$secureHash = md5($vnp_HashSecret . $hashData);
        $secureHash = hash('sha256', $vnp_HashSecret . $hashData);
        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                //success
                $order_id = $_GET['vnp_TxnRef'];

                $order = Order::find($order_id);
                if ($order->order_status == -1) {
                    $order->order_status = 0;
                    $order->message = "Đã thanh toán VNPAY";
                    $order->save();

                    $email = $order->shipping->shipping_email;
                    $shipping = $order->shipping;
                    $cart = Cart::content();
                    if ($cart) {
                        $emailJob = new SendCheckoutMail($email, $order, $shipping, $cart);
                        $emailJob->delay(Carbon::now()->addSeconds(10));
                        dispatch($emailJob);
                        Cart::destroy();
                    }
                } else {
                    $cart = Cart::content();
                }

                $shipping = $order->shipping;
                Session::put('history', 'place-order');
                return view('pages.checkout.success', compact('cart', 'shipping', 'order'));
            } else {
                $order_id = $_GET['vnp_TxnRef'];
                $order = Order::find($order_id);
                $order->shipping->delete();
                $order->delete();
                return Redirect::to('/checkout')->with('message', 'Thanh toán không thành công');
            }
        } else {
            echo "Chu ky khong hop le";
        }
    }

    public function update_order($id, Request $request)
    {
        $order_detail  = OrderDetail::where('order_id', $id)->get();
        $data = $request->all();
        $sum = 0;
        foreach ($order_detail as $item) {
            $item->product_sales_quantity = $data[$item->order_detail_id];
            $sum += $item->product_price * $item->product_sales_quantity;
            $item->save();
        }
        $order = Order::find($id);
        $order->order_total = $sum;
        $order->save();
        return redirect()->back()->with('message', 'Cập nhật đơn hàng thành công!');
    }
}
