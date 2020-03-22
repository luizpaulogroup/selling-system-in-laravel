<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;

use App\Models\Admin;

class AuthController extends Controller
{

    private $objAdmin;

    public function __construct(){
        
        $this->objAdmin = new Admin();

    }

    public function index()
    {
        
        if(session()->has('admin')){
            return redirect('client');
        }

        $title = "Auth";

        return view('auth.index', array(
            'title' => $title,
        ));
    }

    public function init(AdminRequest $request)
    {

        $title = "Auth";

        $email = $request->email;
        $password = $request->password;

        $admin = $this->objAdmin
            ->where('email', $email)
            ->where('password', $password)
            ->where('status', 'A')
            ->first();

        if(!$admin){
            return view('auth.index', array(
                'title' => $title,
            ));
        }

        $request->session()->put('admin', array(
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
        ));

        return redirect('client');

    }

}