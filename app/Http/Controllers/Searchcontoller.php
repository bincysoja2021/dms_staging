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
    public function normal_search(Request $req)
    {
      if ($req->ajax())
      {
          $user_name_exist=Document::where('user_name',$req->search_key)->exists();
          $data = Document::where('deleted_at',NULL)->latest()->get();
          return Datatables::of($data)
              ->addIndexColumn()
              ->addColumn('action', function($row)
              {
                $actionBtn = '
                              <a href="'.route('download.pdf', $row->filename).'"><i class="fa fa-download" aria-hidden="true"></i></a>';
                return $actionBtn;
              })
              ->addColumn('thumbnail', function ($row) {
              $actionBtn ="<img src='".route('load_images', $row->thumbnail)."'  width='100px' height='100px' >";
              return $actionBtn;
              })
              ->rawColumns(['thumbnail','action'])
              ->make(true);
       }    
      
    }
}
