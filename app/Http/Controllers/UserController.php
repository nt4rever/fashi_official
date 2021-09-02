<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function AuthLogin()
    {
        $login = Auth::id();
        if ($login) {
            return Redirect::to('/dashboard');
        } else {
            return Redirect::to('/login-auth')->send();
        }
    }


    public function index()
    {

        $this->AuthLogin();
        $admin = User::with('roles')->orderBy('id', 'desc')->paginate(25);

        return view('admin.users.all_user', compact('admin'));
    }

    public function assign_roles(Request $request)
    {
        $this->AuthLogin();
        if (Auth::id() == $request->admin_id) {
            return redirect()->back();
        }
        $user = User::where('email', $request->admin_email)->first();
        // if ($user->id == 1) {
        //     Session::flash('message', 'Cấp quyền thất bại!');
        //     return redirect()->back();
        // }
        $user->roles()->detach(); // delete all connection roles
        if ($request->author_role) {
            $user->roles()->attach(Roles::where('name', 'author')->first());
        }
        if ($request->admin_role) {
            $user->roles()->attach(Roles::where('name', 'admin')->first());
        }
        if ($request->user_role) {
            $user->roles()->attach(Roles::where('name', 'user')->first());
        }

        Session::flash('message', 'Cấp quyền thành công!');
        return redirect()->back();
    }

    public function delete_user_roles($id)
    {
        $this->AuthLogin();
        if (Auth::id() == $id) {
            return redirect()->back();
        }
        $user = User::find($id);
        if ($user) {
            $user->roles()->detach();
            $user->delete();
        }

        Session::flash('message', 'Xoá auth user thành công!');
        return redirect()->back();
    }
}
