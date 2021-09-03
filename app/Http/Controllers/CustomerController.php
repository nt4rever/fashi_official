<?php

namespace App\Http\Controllers;

use App\Jobs\SendForgetPasswordMail;
use App\Models\Category;
use App\Models\ContactPage;
use App\Models\Customer;
use App\Models\SocialCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\View\Components\Recusive;
use Illuminate\Support\Facades\File;
use App\Rules\Captcha;
use Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Storage;

class CustomerController extends Controller
{
    private $customer;
    private $category;

    public function __construct(Customer $customer, Category $category)
    {
        $this->customer = $customer;
        $this->category = $category;
    }

    // public function getCategory($parentId)
    // {
    //     $data = $this->category->where('category_status', 0)->get();
    //     $recusive = new Recusive($data);
    //     $htmlOption = $recusive->categoryRecusiveHome($parentId);
    //     return $htmlOption;
    // }

    // public function getCategoryOption($parentId)
    // {
    //     $data = $this->category->where('category_status', 0)->get();
    //     $recusive = new Recusive($data);
    //     $htmlOption = $recusive->categoryRecusive($parentId);
    //     return $htmlOption;
    // }

    public function login()
    {
        if (Session::get('customer_id')) {
            return redirect('/home');
        }
        if (!Session::get('history')) {
            Session::put('history', url()->previous());
        }
        return view('pages.customer.login');
    }

    public function login_handle(Request $request)
    {
        $data = $request->validate([
            'customer_email' => 'required',
            'customer_password' => 'required',
            // 'g-recaptcha-response' => new Captcha(),         //dòng kiểm tra Captcha
        ]);

        $cus = $this->customer->where('customer_email', $request->customer_email)
            ->where('customer_password', md5($request->customer_password))->first();
        if ($cus) {
            if ($cus->customer_status == 1) {
                Session::put('message', 'Your account has been blocked!');
                Session::put('username', $request->customer_email);
                return redirect()->back();
            }
            Session::put('customer_name', $cus->customer_name);
            Session::put('customer_email', $cus->customer_email);
            Session::put('customer_id', $cus->customer_id);
            $history = Session::get('history');
            if ($history) {
                if (strpos($history, "register-customer")) {
                    return Redirect::to('/home');
                }
                Session::put('history', null);
                return Redirect::to($history);
            }
            return redirect('/home');
        } else {
            Session::put('message', 'Email or password incorrect!');
            Session::put('username', $request->customer_email);
            return redirect()->back();
        }
    }

    public function logout()
    {
        Session::put('customer_name', null);
        Session::put('customer_email', null);
        Session::put('customer_id', null);
        $history = Session::get('history');
        if ($history == 'place-order') {
            Session::put('history', null);
            return Redirect::to('/home');
        }
        return redirect()->back();
    }

    public function register()
    {
        return view('pages.customer.register');
    }

    public function register_handle(Request $request)
    {
        $this->validation($request);

        $result = Customer::where('customer_email', $request->customer_email)->first();
        if ($result) {
            Session::put('message', 'Account has already exists!');
            return Redirect::to('/register-customer');
        }

        $data = $request->all();
        $customer = new Customer();

        $email = $data['customer_email'];
        $email = trim($email);
        $email = stripslashes($email);
        $email = htmlspecialchars($email);
        $email = strtolower($email);

        $customer->customer_email = $email;
        $customer->customer_name = $data['customer_name'];
        $customer->customer_password = md5($data['customer_password']);
        $customer->save();
        Session::put('message', 'Register success, please login!');
        return Redirect::to('/login-customer');
    }

    public function validation($request)
    {
        return $this->validate($request, [
            'customer_email' => 'email|max:100',
            'customer_password' => 'required|max:255',
            'customer_confirm_password' => 'same:customer_password|max:255',
            'customer_name' => 'required|max:100',
            // 'g-recaptcha-response' => new Captcha(),
        ]);
    }

    public function show_information()
    {
        $id = Session::get('customer_id');
        if (isset($id)) {
            $user = Customer::where('customer_id', $id)->first();
            return view('pages.customer.show_information', compact('user'));
        } else {
            return Redirect::to('/home');
        }
    }

    public function save_information(Request $request)
    {
        $id = Session::get('customer_id');
        if (isset($id)) {
            $user = Customer::where('customer_id', $id)->first();
            $data = array();
            $data['customer_name'] = $request->customer_name;
            $data['customer_phone'] = $request->customer_phone;
            $data['customer_address'] = $request->customer_address;
            // $data['customer_email'] = $request->customer_email;
            $get_image = $request->file('customer_image');
            if ($get_image) {
                $get_name_image = $get_image->getClientOriginalName();
                $name_image = current(explode('.', $get_name_image));
                $new_image = $name_image . date('dmYHis')  . '.' . $get_image->getClientOriginalExtension();
                $get_image->move('uploads/avatar', $new_image);
                $data['customer_image'] = $this->upload_to_drive($new_image);
                // $data['customer_image'] = url('/') . '/uploads/customer/' . $new_image;

                // $old_image = $user->customer_image;
                // if (isset($old_image)) {
                //     $image_path = "uploads/customer/" . $old_image;
                //     if (File::exists($image_path)) {
                //         File::delete($image_path);
                //     }
                // }
            }
            Customer::where('customer_id', $id)->update($data);
            Session::put('customer_name', $request->customer_name);
            // Session::put('customer_email',  $request->customer_email);
            return redirect()->back();
        } else {
            return Redirect::to('/home');
        }
    }

    public function save_change_password(Request $request)
    {
        $id = Session::get('customer_id');
        if (isset($id)) {
            $user = Customer::where('customer_id', $id)->first();
            if (($user->customer_password == md5($request->old_password)) && ($request->new_password == $request->repeat_new_password)) {
                Customer::where('customer_id', $id)->update(['customer_password' => md5($request->new_password)]);
                return redirect()->back();
            } else {
                Session::put('message', 'Error!');
                return redirect()->back();
            }
        } else {
            return Redirect::to('/home');
        }
    }



    public function forget_password()
    {
        if (Session::get('customer_id')) {
            return redirect('/home');
        }
        return view('pages.customer.forget_password');
    }

    public function mail_forget_password(Request $request)
    {
        $cus = Customer::where('customer_email', $request->customer_email)->first();
        if ($cus) {
            $token_random = Str::random();
            $cus->customer_token = $token_random;
            $link_reset_pass = url('/update-new-password?email=' . $cus->customer_email . '&token=' . $token_random);
            $email = new SendForgetPasswordMail($link_reset_pass, $cus, $cus->customer_email);
            dispatch($email);
            $cus->save();
            Session::put('message', 'Check your email to recover your password!');
            return Redirect::to('/login-customer');
        } else {
            Session::put('message', 'Account does not exist!');
            return Redirect::to('/forget-password-customer');
        }
    }

    public function update_new_password()
    {
        if (Session::get('customer_id')) {
            return redirect('/home');
        }
        return view('pages.customer.update_new_password');
    }

    public function update_new_password_handle(Request $request)
    {
        $this->validate($request, [
            'customer_email' => 'email|max:100',
            'customer_password' => 'required|max:255',
            'customer_confirm_password' => 'same:customer_password|max:255',
        ]);

        if (Session::get('customer_id')) {
            return redirect('/home');
        }

        $token_random = $request->token;;
        $customer_email = $request->customer_email;
        $cus = Customer::where('customer_email', $customer_email)->first();
        if ($cus) {
            if ($cus->customer_token == $token_random) {
                $cus->customer_password = md5($request->customer_password);
                $cus->customer_token = '';
                $cus->save();
                Session::put('message', 'Change password success!');
            } else {
                Session::put('message', 'Token!');
            }
        } else {
            Session::put('message', 'account!');
        }
        return Redirect::to('/login-customer');
    }

    public function login_facebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback_facebook()
    {
        $provider = Socialite::driver('facebook')->user();

        $account = SocialCustomer::where('provider', 'facebook')->where('provider_user_id', $provider->getId())->first();
        if ($account != NULL) {
            $cus = Customer::where('customer_id', $account->user)->first();
            if ($cus->customer_status == 1) {
                Session::put('message', 'Your account has been blocked!');
                return Redirect::to('/login-customer');
            }
            Session::put('customer_name', $cus->customer_name);
            Session::put('customer_email', $cus->customer_email);
            Session::put('customer_id', $cus->customer_id);
            $history = Session::get('history');
            if ($history) {
                if (strpos($history, "register-customer")) {
                    Session::put('history', null);
                    return Redirect::to('/home');
                }
                Session::put('history', null);
                return Redirect::to($history);
            }
            return redirect('/home');
        } elseif ($account == NULL) {
            $cus_login = new SocialCustomer([
                'provider_user_id' => $provider->getId(),
                'provider_user_email' => $provider->getEmail(),
                'provider' => 'facebook'
            ]);

            $customer = Customer::where('customer_email', $provider->getEmail())->first();
            if (!$customer) {
                $customer = Customer::create([
                    'customer_name' => $provider->getName(),
                    'customer_email' => $provider->getEmail(),
                    'customer_image' => $provider->getAvatar(),
                    'customer_password' => md5(date('dmYHis')),
                ]);
            }
            $cus_login->customer()->associate($customer);
            $cus_login->save();

            $account_new = Customer::where('customer_id', $cus_login->user)->first();
            Session::put('customer_name', $account_new->customer_name);
            Session::put('customer_email', $account_new->customer_email);
            Session::put('customer_id', $account_new->customer_id);
            $history = Session::get('history');
            if ($history) {
                if (strpos($history, "register-customer")) {
                    Session::put('history', null);
                    return Redirect::to('/home');
                }
                Session::put('history', null);
                return Redirect::to($history);
            }
            return redirect('/home');
        }
    }

    public function login_google()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback_google()
    {
        $provider = Socialite::driver('google')->user();
        // dd($provider);
        $account = SocialCustomer::where('provider', 'google')->where('provider_user_id', $provider->getId())->first();
        if ($account != NULL) {
            $cus = Customer::where('customer_id', $account->user)->first();
            if ($cus->customer_status == 1) {
                Session::put('message', 'Your account has been blocked!');
                return Redirect::to('/login-customer');
            }
            Session::put('customer_name', $cus->customer_name);
            Session::put('customer_email', $cus->customer_email);
            Session::put('customer_id', $cus->customer_id);
            // Session::put('customer_img', $cus->customer_image);
            $history = Session::get('history');
            if ($history) {
                if (strpos($history, "register-customer")) {
                    Session::put('history', null);
                    return Redirect::to('/home');
                }
                Session::put('history', null);
                return Redirect::to($history);
            }
            return redirect('/home');
        } elseif ($account == NULL) {
            $cus_login = new SocialCustomer([
                'provider_user_id' => $provider->getId(),
                'provider_user_email' => $provider->getEmail(),
                'provider' => 'google'
            ]);

            $customer = Customer::where('customer_email', $provider->getEmail())->first();
            if (!$customer) {
                $customer = Customer::create([
                    'customer_name' => $provider->getName(),
                    'customer_email' => $provider->getEmail(),
                    'customer_image' => $provider->getAvatar(),
                    'customer_password' => md5(date('dmYHis')),
                ]);
            }
            $cus_login->customer()->associate($customer);
            $cus_login->save();

            $account_new = Customer::where('customer_id', $cus_login->user)->first();
            Session::put('customer_name', $account_new->customer_name);
            Session::put('customer_email', $account_new->customer_email);
            Session::put('customer_id', $account_new->customer_id);
            // Session::put('customer_img', $account_new->customer_image);
            $history = Session::get('history');
            if ($history) {
                if (strpos($history, "register-customer")) {
                    Session::put('history', null);
                    return Redirect::to('/home');
                }
                Session::put('history', null);
                return Redirect::to($history);
            }
            return redirect('/home');
        }
    }

    private function upload_to_drive($file_name)
    {
        $file_path = public_path("uploads/avatar/" . $file_name);
        $file_data = File::get($file_path);
        $contents = collect(Storage::cloud()->listContents('/', true));
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', 'avatar')
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
