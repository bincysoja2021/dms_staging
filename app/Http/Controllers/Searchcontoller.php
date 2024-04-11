<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Notification;
use Yajra\DataTables\DataTables;
use Storage;

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
