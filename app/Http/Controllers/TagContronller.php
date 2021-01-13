<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class TagContronller extends Controller
{
    public function tag()
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
        if (sizeof($data) < 7) {
            echo json_encode($data);
        } else {
            echo json_encode(array_slice($data, '0', '7'));
        }
    }

    public function fix()
    {
        $order = Order::all();
        foreach ($order as $item) {
            $item->order_total = filter_var(
                $item->order_total,
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );
            $item->save();
        }
        echo json_encode($order);
    }
}
