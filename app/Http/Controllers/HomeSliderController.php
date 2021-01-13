<?php

namespace App\Http\Controllers;

use App\Models\HomeSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class HomeSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $home_slider = HomeSlider::paginate(5);
        return view('admin.homeslider.index', compact('home_slider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.homeslider.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'home_slider_desc' => 'required',
            'home_slider_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $home_slider = new HomeSlider();
        $home_slider->home_slider_desc = $request->home_slider_desc;
        $home_slider->home_slider_sale = $request->home_slider_sale;

        $get_image = $request->file('home_slider_image');
        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . date('dmYHis')  . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('uploads/homeslider', $new_image);
            $home_slider->home_slider_image = $new_image;
        } else {
            $home_slider->home_slider_image = '';
        }
        $home_slider->save();
        Session::flash('message', 'Thêm slide thành công!');
        return Redirect::to('/ad/home-slider');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $home_slider = HomeSlider::find($id);
        return view('admin.homeslider.edit', compact('home_slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'home_slider_desc' => 'required',
            'home_slider_image' => '|image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        $home_slider = HomeSlider::find($id);
        $home_slider->home_slider_desc = $request->home_slider_desc;
        $home_slider->home_slider_sale = $request->home_slider_sale;
        $get_image = $request->file('home_slider_image');
        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . date('dmYHis')  . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('uploads/homeslider', $new_image);

            $image = $home_slider->home_slider_image;
            $image_path = "uploads/homeslider/" . $image;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $home_slider->home_slider_image = $new_image;
        }
        $home_slider->save();

        Session::flash('message', 'Sửa slide thành công!');
        return Redirect::to('/ad/home-slider');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $home_slider = HomeSlider::find($id);
        $image = $home_slider->home_slider_image;
        $image_path = "uploads/homeslider/" . $image;
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        $home_slider->delete();
        Session::flash('message', 'Xoá slide thành công!');
        return Redirect::to('/ad/home-slider');
    }
}
