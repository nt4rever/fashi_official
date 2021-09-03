<?php

namespace App\Jobs;

use App\Mail\ConfirmOrderMail;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class SendConfirmOrderMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $email_address;
    public $shipping;
    // public $file_attach;
    public $order_id;
    public function __construct($email_address, $shipping, $order_id)
    {
        $this->email_address = $email_address;
        $this->shipping = $shipping;
        // $this->file_attach = $file_attach;
        $this->order_id = $order_id;
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
        $output = $output . '<p>Phương thức thanh toán: ' . $payment . '</p>

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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->print_checkout_convert($this->order_id));
        $file_attach = public_path().'/hoadon/' . $this->order_id . '_' . date('dmYHis') . '.pdf';
        $pdf->save($file_attach);
        $email = new ConfirmOrderMail($this->shipping, $file_attach);

        Mail::to($this->email_address)->send($email);
        if (!Mail::failures()) {
            unlink($file_attach);
        }
    }
}
