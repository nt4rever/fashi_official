<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataAddressController extends Controller
{
    public function district(Request $request)
    {

        // $quanhuyen = DB::table('tbl_quanhuyen')->where('matp', $request->thanhpho)->get();
        $client = new Client();
        $response = $client->get('https://provinces.open-api.vn/api/p/'.$request->thanhpho.'?depth=2');
        $quanhuyen = json_decode($response->getBody());
        echo ' <option value="" disabled selected>Chọn quận - huyện</option>';
        foreach ($quanhuyen->districts as $item) {
            echo '<option value="' . $item->name . '" data-id="'.$item->code.'">' . $item->name . '</option>';
        }
    }

    public function wards(Request $request)
    {
        // $phuongxa = DB::table('tbl_xaphuongthitran')->where('maqh', $request->quanhuyen)->get();
        $client = new Client();
        $response = $client->get('https://provinces.open-api.vn/api/d/'.$request->quanhuyen.'?depth=2');
        $phuongxa = json_decode($response->getBody());
        echo '<option value="" disabled selected>Chọn xã - phường</option>';
        foreach ($phuongxa->wards as $item) {
            echo '<option value="' . $item->name . '" data-id="'.$item->code.'">' . $item->name . '</option>';
        }
    }

}
