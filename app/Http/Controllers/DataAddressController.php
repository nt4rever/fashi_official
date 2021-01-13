<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataAddressController extends Controller
{
    public function district(Request $request)
    {

        $quanhuyen = DB::table('tbl_quanhuyen')->where('matp', $request->thanhpho)->get();
        echo ' <option value="" disabled selected>Chọn quận - huyện</option>';
        foreach ($quanhuyen as $item) {
            echo '<option value="' . $item->maqh . '">' . $item->name . '</option>';
        }
    }

    public function wards(Request $request)
    {
        $phuongxa = DB::table('tbl_xaphuongthitran')->where('maqh', $request->quanhuyen)->get();
        echo '<option value="" disabled selected>Chọn xã - phường</option>';
        foreach ($phuongxa as $item) {
            echo '<option value="' . $item->xaid . '">' . $item->name . '</option>';
        }
    }

}
