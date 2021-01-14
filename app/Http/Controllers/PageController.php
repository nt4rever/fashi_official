<?php

namespace App\Http\Controllers;

use App\Models\ContactPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PageController extends Controller
{
    public function AuthLogin()
    {
        $login = Auth::id();
        if ($login) {
            return Redirect::to('/dashboard');
        } else {
            return Redirect::to('/admin')->send();
        }
    }

    public function contact()
    {
        $this->AuthLogin();
        $contact = ContactPage::first();
        return view('admin.static_page.contact', compact('contact'));
    }

    public function save_contact(Request $request)
    {
        $this->AuthLogin();
        $con = ContactPage::first();
        if ($con) {
            $con->map_frame = $request->map_frame;
            $con->phone = $request->phone;
            $con->email = $request->email;
            $con->address = $request->address;
            $con->social = $request->social;
            $con->introduce = $request->introduce;
            $con->save();
        } else {
            $con =  new ContactPage();
            $con->map_frame = $request->map_frame;
            $con->phone = $request->phone;
            $con->email = $request->email;
            $con->address = $request->address;
            $con->social = $request->social;
            $con->introduce = $request->introduce;
            $con->save();
        }
        return redirect()->back();
    }
}
