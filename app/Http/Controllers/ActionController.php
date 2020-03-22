<?php

namespace App\Http\Controllers;

class ActionController extends Controller
{

    public function logout()
    {
        session()->forget('admin');
        return redirect('auth');
    }

}
