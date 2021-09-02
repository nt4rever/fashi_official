<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Roles;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function config()
    {
        $role_admin = new Roles();
        $role_admin->name = "admin";
        $role_admin->save();
        $role_author = new Roles();
        $role_author->name = "author";
        $role_author->save();
        $role_user = new Roles();
        $role_user->name = "user";
        $role_user->save();

        $admin = new User();
        $admin->email = "admin@gmail.com";
        $admin->name = "admin";
        $admin->password = "123456";
        $admin->phone = "0336757208";
        $admin->save();
        $admin->roles()->attach(Roles::where('name', 'admin')->first());
        echo "success!";
    }

    public function api()
    {
        $client = new Client();
        $response = $client->get('https://provinces.open-api.vn/api/?depth=1');
        $data = json_decode($response->getBody());
        foreach($data as $item){
            echo $item->name;
        }
    }
}
