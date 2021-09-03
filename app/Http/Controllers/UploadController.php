<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Storage;

class UploadController extends Controller
{
    public function ckeditor_image(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move('uploads/ckeditor', $fileName);
            $url = $this->upload_to_drive($fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            // $url = asset('uploads/ckeditor/' . $fileName);
            $msg = "Tải ảnh thành công!";
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum,'$url','$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    public function ckeditor_browser(Request $request)
    {
        $dir = "/1A7qhQJ6nugJ7Ibve75_At4ihyeD0aWvX/";
        $recursive = false;
        $contents = collect(Storage::cloud()->listContents($dir,$recursive));
        $fileNames = array();
        foreach($contents as $item){
            array_push($fileNames, $item['basename']);;
        }
        // $paths = glob(public_path('uploads/ckeditor/*'));
        // $fileNames = array();
        // foreach ($paths as $path) {
        //     array_push($fileNames, basename($path));
        // }
        $data = array(
            'fileNames' => $fileNames
        );
        return view('admin.images.file_browser')->with($data);
    }

    public function ckeditor_delete(Request $request)
    {
        $path = $request->path;
        // if (File::exists($path)) {
        //     File::delete($path);
        //     return true;
        // }
        $this->delete_from_drive($path);
        return true;
    }

    private function upload_to_drive($file_name)
    {
        $file_path = public_path("uploads/ckeditor/" . $file_name);
        $file_data = File::get($file_path);
        $contents = collect(Storage::cloud()->listContents('/', true));
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', 'ckeditor')
            ->first();
        $file_name = $dir['path'] . "/" . $file_name;
        Storage::cloud()->put($file_name, $file_data);
        $url = Storage::cloud()->url($file_name);
        return $url;
    }

    private function delete_from_drive($filename)
    {
        // $contents = collect(Storage::cloud()->listContents("/", true));
        // $file = $contents
        //     ->where('type', '=', 'file')
        //     ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
        //     ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
        //     ->first();
        Storage::cloud()->delete($filename);
    }
}
