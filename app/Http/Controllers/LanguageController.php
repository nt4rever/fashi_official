<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function index($locate)
    {
        if ($locate) {
            Session::put('language', $locate);
        }
        return redirect()->back();
    }
}
