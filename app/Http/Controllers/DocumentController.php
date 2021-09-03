<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use File;

class DocumentController extends Controller
{
    public function create(){
        Storage::cloud()->put('hi.txt','cacc');
        $url = Storage::cloud()->url('hi.txt');
        dd($url);
    }

    public function list(){
        $dir = "/1A7qhQJ6nugJ7Ibve75_At4ihyeD0aWvX/";
        $recursive = false;
        $contents = collect(Storage::cloud()->listContents($dir,$recursive));
        foreach($contents as $item){
            echo $item['basename'];
        }
       
    }
}
