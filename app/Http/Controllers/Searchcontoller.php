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
      $data=Document::where('deleted_at',NULL)->latest()->get();
      return view('admin.search',compact('data'));
    }

    public function advanced_search()
    {
      return view('admin.advanced_search');
    }
    public function normal_search(Request $req)
    {
      if ($req->ajax())
      {
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


    public function normal_ajax_search(Request $req)
    {
      if ($req->ajax())
      {
        $invoice_number_exist=Document::where('invoice_number',$req->form)->where('deleted_at',NULL)->exists();
        if($invoice_number_exist == true)
        {
            $data = Document::where('invoice_number',$req->form)->where('deleted_at',NULL)->latest()->get();
        }
        else
        {
            $data = Document::where('deleted_at',NULL)->latest()->get();
        }
        return $data;
       }    
      
    }
   
   

}
