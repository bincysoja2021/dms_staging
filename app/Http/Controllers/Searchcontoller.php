<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Searchcontoller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search()
    {
      return view('admin.search');
    }

    public function advanced_search()
    {
      return view('admin.advanced_search');
    }
}
