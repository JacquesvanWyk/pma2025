<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function index()
    {
        return view('about.index');
    }

    public function principles()
    {
        return view('about.principles');
    }

    public function support()
    {
        return view('about.support');
    }
}
