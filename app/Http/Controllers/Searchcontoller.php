<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Notification;
use Yajra\DataTables\DataTables;
use Storage;
use Carbon\Carbon;

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
/**********************************
   Date        : 15/04/2024
   Description :  Advanced search
**********************************/
    public function advanced_search()
    {
      return view('admin.advanced_search');
    }
/****************************************
   Date        : 15/04/2024
   Description :  normal searching
****************************************/    
    public function normal_search(Request $req)
    {
      if ($req->ajax())
      {
          $data = Document::where('deleted_at',NULL)->latest()->get();
          return Datatables::of($data)
              ->addIndexColumn()
              ->addColumn('action', function($row)
              {
                $actionBtn = '<a href="'.route('download.pdf', $row->filename).'"><i class="fa fa-download" aria-hidden="true"></i></a>';
                return $actionBtn;
              })
                ->addColumn('date', function ($item) {
              $actionBtn =date('d-m-Y',strtotime($item->date));
              return $actionBtn;
              })
              ->addColumn('thumbnail', function ($row) {
              $actionBtn ="<img src='".route('load_images', $row->thumbnail)."'  width='100px' height='100px' >";
              return $actionBtn;
              })
              ->rawColumns(['date','thumbnail','action'])
              ->make(true);
       }    
      
    }
/****************************************
   Date        : 15/04/2024
   Description :  normal ajax search
****************************************/

    public function normal_ajax_search(Request $req)
    {
      if ($req->ajax())
      {
        $invoice_number_exist=Document::where('invoice_number',$req->form)->where('deleted_at',NULL)->exists();
        $sales_order_number_exist=Document::where('sales_order_number',$req->form)->where('deleted_at',NULL)->exists();
        $shipping_bill_number_exist=Document::where('shipping_bill_number',$req->form)->where('deleted_at',NULL)->exists();
        $user_name_exist=Document::where('user_name',$req->form)->where('deleted_at',NULL)->exists();
        if($invoice_number_exist == true)
        {
            $data = Document::where('invoice_number',$req->form)->where('deleted_at',NULL)->latest()->get();
        }
        else if($sales_order_number_exist == true)
        {
            $data = Document::where('sales_order_number',$req->form)->where('deleted_at',NULL)->latest()->get();
        }
        else if($shipping_bill_number_exist == true)
        {
            $data = Document::where('shipping_bill_number',$req->form)->where('deleted_at',NULL)->latest()->get();
        }
        else if($user_name_exist == true)
        {
            $data = Document::where('user_name',$req->form)->where('deleted_at',NULL)->latest()->get();
        }
        else
        {
            $data = "";
        }
        return $data;
       }    
      
    }
/***************************************
   Date        : 15/04/2024
   Description :  Advanced search
***************************************/
    public function advanced_ajax_search(Request $req)
    {
      if ($req->ajax())
      {
        $form = Carbon::parse($req->form_date)->format('Y-m-d');
        $to=Carbon::parse($req->to_date)->format('Y-m-d');
        $invoice_number_exist=Document::where('invoice_number',$req->invoice_number)->where('deleted_at',NULL)->exists();
        $sales_order_number_exist=Document::where('sales_order_number',$req->sales_order_number)->where('deleted_at',NULL)->exists();
        $shipping_bill_number_exist=Document::where('shipping_bill_number',$req->shipping_bill_number)->where('deleted_at',NULL)->exists();
        $user_name_exist=Document::where('user_name',$req->form)->where('deleted_at',NULL)->exists();
        $invoice_date_exist=Document::where('invoice_date',$req->invoice_date)->where('deleted_at',NULL)->exists();
        $form_date_exist=Document::where('date',$form)->where('deleted_at',NULL)->exists();
        $to_date_exist=Document::where('date',$to)->where('deleted_at',NULL)->exists();
        if($invoice_number_exist == true)
        {
            $data = Document::where('invoice_number',$req->invoice_number)->where('deleted_at',NULL)->latest()->get();
        }
        else if($sales_order_number_exist == true)
        {
            $data = Document::where('sales_order_number',$req->sales_order_number)->where('deleted_at',NULL)->latest()->get();
        }
        else if($shipping_bill_number_exist == true)
        {
            $data = Document::where('shipping_bill_number',$req->shipping_bill_number)->where('deleted_at',NULL)->latest()->get();
        }
        else if($user_name_exist == true)
        {
            $data = Document::where('user_name',$req->form)->where('deleted_at',NULL)->latest()->get();
        }
        else if($invoice_date_exist == true)
        { 
            $data = Document::where('invoice_date',$req->invoice_date)->where('deleted_at',NULL)->latest()->get();
        }
        else if($form_date_exist == true && $to_date_exist== true)
        { 
            $data = Document::whereBetween('date',[$form,$to])->where('deleted_at',NULL)->latest()->get();
        }
        else if($invoice_number_exist == true && $sales_order_number_exist== true && $shipping_bill_number_exist == true && $invoice_date_exist == true && $form_date_exist == true && $to_date_exist == true)
        { 
            $data = Document::where('invoice_number',$req->invoice_number)->where('sales_order_number',$req->sales_order_number)->where('shipping_bill_number',$req->shipping_bill_number)->where('invoice_date',$req->invoice_date)->whereBetween('date',[$form,$to])->where('deleted_at',NULL)->latest()->get();
        }
        else
        {
            $data = "";
        }
        return $data;
       }    
      
    }
   
   

}
