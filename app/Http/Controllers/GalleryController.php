<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Storage;

class GalleryController extends Controller
{
    private $gallery;

    public function __construct(Gallery $gallery)
    {
        $this->gallery = $gallery;
    }

    public function AuthLogin()
    {
        $login = Auth::id();
        if ($login) {
            return Redirect::to('/dashboard');
        } else {
            return Redirect::to('/login-auth')->send();
        }
    }

    public function list_gallery($id)
    {
        $this->AuthLogin();
        $list = $this->gallery->where('product_id', $id)->get();
        $product_id = $id;
        return view('admin.gallery.list_product_gallery', compact('list', 'product_id'));
    }

    public function reload_product_gallery(Request $request)
    {
        $this->AuthLogin();
        $list = $this->gallery->where('product_id', $request->product_id)->get();
        foreach ($list as $item) {
            echo    "<tr>
                        <td>$item->id</td>
                        <td contenteditable='true' class='edit_gallery_name' data-gal_id='$item->id'>$item->name</td>
                        <td><img src='" . $item->path . "' alt=''
                                style='width: 150px'></td>
                                <td><button class='btn btn-outline-warning' name='delete_gallery'
                                data-gal_id='$item->id'><i class='fas fa-trash-alt'></i></button></td>
                    </tr>";
        }
    }

    public function insert_product_gallery(Request $request, $id)
    {
        $this->AuthLogin();
        try {
            DB::beginTransaction();
            $get_image = $request->file('file');
            if ($get_image) {
                foreach ($get_image as $image) {
                    $get_name_image = $image->getClientOriginalName();
                    $name_image = current(explode('.', $get_name_image));
                    $new_image = $name_image . date('dmYHis')  . '.' . $image->getClientOriginalExtension();
                    $image->move('uploads/gallery', $new_image);
                    
                    $data = array();
                    $data['product_id'] = $id;
                    $data['name'] = $new_image;
                    $data['path'] = $this->upload_to_drive($new_image);
                    $this->gallery->create($data);
                }
            }
            DB::commit();
            return redirect()->back();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function update_product_gallery_name(Request $request)
    {
        $this->AuthLogin();
        try {
            // $data = array();
            // $data['name'] = $request->gallery_text;
            // $this->gallery->find($request->gallery_id)->update($data);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function delete_product_gallery(Request $request)
    {
        $this->AuthLogin();
        try {
            $gallery = $this->gallery->find($request->gallery_id);
            $this->delete_from_drive($gallery->name);
            // $image_path = 'uploads/gallery/' . $gallery->path;
            // if (File::exists($image_path)) {
            //     File::delete($image_path);
            // }
            $gallery->delete();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    private function upload_to_drive($file_name)
    {
        $file_path = public_path("uploads/gallery/" . $file_name);
        $file_data = File::get($file_path);
        $contents = collect(Storage::cloud()->listContents('/', true));
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', 'gallery')
            ->first();
        $file_name = $dir['path'] . "/" . $file_name;
        Storage::cloud()->put($file_name, $file_data);
        $url = Storage::cloud()->url($file_name);
        return $url;
    }

    private function delete_from_drive($filename)
    {
        $contents = collect(Storage::cloud()->listContents("/", true));
        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first();
        Storage::cloud()->delete($file['path']);
    }

}
