<?php

namespace App\Http\Controllers;

class ThemeController extends Controller
{

    public function index()
    {

        if(!session()->has('admin')){
            return redirect('auth');
        }
        
        $title = "Venda - LISTA";

        $session = session()->get('admin');

        return view('theme.index', array(
            'title' => $title,
            'session' => $session
        ));
    }

}