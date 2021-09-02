<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
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

    // login auth
    public function login_auth()
    {
        $login = Auth::id();
        if ($login) {
            return Redirect::to('/dashboard');
        }
        return view('admin.custom_auth.login_auth');
    }

    public function login_auth_handle(Request $request)
    {
        $this->validate($request, [
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|max:255'
        ]);

        if (Auth::attempt(['email' => $request->admin_email, 'password' => $request->admin_password])) {
            return Redirect::to('/dashboard');
        } else {
            Session::flash('message', 'Tài khoản hoặc mật khẩu không đúng!');
            Session::flash('username', $request->admin_email);
            return Redirect::to('/login-auth');
        }
    }
    // end login auth

    // add user auth
    public function register_auth()
    {
        $this->AuthLogin();
        return view('admin.custom_auth.register');
    }

    public function register_auth_handle(Request $request)
    {
        $this->AuthLogin();
        $this->validation($request);
        $data = $request->all();
        $admin = new User();
        $admin->name = $data['admin_name'];
        $admin->email = $data['admin_email'];
        $admin->phone = $data['admin_phone'];
        $admin->password = $data['admin_password'];
        $admin->save();
        $admin->roles()->attach(Roles::where('name', 'user')->first());
        return Redirect::to('/auth-users');
    }

    public function validation($request)
    {
        return $this->validate($request, [
            'admin_phone' => 'required|digits_between:7,12',
            'admin_name' => 'required|max:50',
            'admin_email' => 'required|email|max:100',
            'admin_password' => 'required|max:255',
            'admin_repeat_password' => 'required|max:255|same:admin_password',
        ]);
    }
    // end add user auth

    public function my_account($id)
    {
        $this->AuthLogin();
        if ($id != Auth::user()->id) {
            return Redirect::to('/dashboard');
        }
        $account = User::find($id);
        return view('admin.account.profile', compact('account'));
    }

    public function save_my_account(Request $request)
    {
        $this->validate($request, [
            'admin_id' => 'required',
            'admin_name' => 'required',
            'admin_phone' => 'required|numeric',
            'admin_password' => 'required',
            'admin_repeat_password' => 'required|same:admin_password'
        ]);

        $account = User::findOrFail($request->admin_id);
        if(!Hash::check($request->admin_old_password,$account->password)){
            return redirect()->back()->with('message', 'Old Password Incorrect');
        }
        $account->name = $request->admin_name;
        $account->phone = $request->admin_phone;
        $account->password = $request->admin_password;
        $account->save();
        if ($request->admin_old_password != $request->admin_password) {
            Auth::logout();
            return Redirect::to('/login-auth');
        }
        return redirect()->back()->with('message', 'Thay đổi thông tin cá nhân thành công!');
    }
}
