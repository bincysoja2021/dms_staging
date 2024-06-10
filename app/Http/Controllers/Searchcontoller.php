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

    public function normal_ajax_search_test(Request $req)
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

    public function normal_ajax_search(Request $req)
    {
        if ($req->ajax())
        {
          if($req->form!==null && $req->tags!==null)
          {
              
              $invoice_number_exist = Document::where('document_type', $req->tags)->where('invoice_number', $req->form)->where('deleted_at', NULL)->exists();
              $sales_order_number_exist = Document::where('document_type', $req->tags)->where('sales_order_number', $req->form)->where('deleted_at', NULL)->exists();
              $shipping_bill_number_exist = Document::where('document_type', $req->tags)->where('shipping_bill_number', $req->form)->where('deleted_at', NULL)->exists();
              $user_name_exist = Document::where('user_name', $req->form)->where('document_type', $req->tags)->where('deleted_at', NULL)->exists();

              if ($invoice_number_exist)
              {
                  $data = Document::where('document_type', $req->tags)->where('invoice_number', $req->form)->where('deleted_at', NULL)->latest()->get();
              }
              else if ($sales_order_number_exist)
              {
                  $data = Document::where('document_type', $req->tags)->where('sales_order_number', $req->form)->where('deleted_at', NULL)->latest()->get();
              }
              else if ($shipping_bill_number_exist)
              {
                  $data = Document::where('document_type', $req->tags)->where('shipping_bill_number', $req->form)->where('deleted_at', NULL)->latest()->get();
              }
              
              else
              {
                  $data = "";
              }
              return $data;
          }
          else if($req->form!==null)
          {
                 // $searchTerm = '%' . $req->form . '%';
              $invoice_number_exist = Document::where('invoice_number', $req->form)->where('deleted_at', NULL)->exists();
              $sales_order_number_exist = Document::where('sales_order_number', $req->form)->where('deleted_at', NULL)->exists();
              $shipping_bill_number_exist = Document::where('shipping_bill_number', $req->form)->where('deleted_at', NULL)->exists();
              $user_name_exist = Document::where('user_name', $req->form)->where('deleted_at', NULL)->exists();

              if ($invoice_number_exist)
              {
                  $data = Document::where('invoice_number', $req->form)->where('deleted_at', NULL)->latest()->get();
              }
              else if ($sales_order_number_exist)
              {
                  $data = Document::where('sales_order_number', $req->form)->where('deleted_at', NULL)->latest()->get();
              }
              else if ($shipping_bill_number_exist)
              {
                  $data = Document::where('shipping_bill_number', $req->form)->where('deleted_at', NULL)->latest()->get();
              }
              
              else
              {
                  $data = "";
              }
              return $data;
          }
          else
          {
             // $searchTerm = '%' . $req->form . '%';
            $document_type_exist =  Document::where('document_type', $req->tags)->where('deleted_at', NULL)->exists();

            if ($document_type_exist==true)
            {
                $data = Document::where('document_type', $req->tags)->where('deleted_at', NULL)->latest()->get();
            }
            
            return $data;
          }
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
        $document_type_exist=Document::where('document_type',$req->prefixtags)->where('deleted_at',NULL)->exists();
        $form_date_exist=Document::where('invoice_date',$form)->where('deleted_at',NULL)->exists();
        $to_date_exist=Document::where('invoice_date',$to)->where('deleted_at',NULL)->exists();
        

        if($req->prefixtags !==null  && $req->form_date !==null  && $req->to_date !==null )
        {

            if($document_type_exist == true && $req->prefixtags == "Invoice")
            {
          
              $data = Document::where('document_type',$req->prefixtags)->whereBetween('invoice_date',[$form,$to])->where('deleted_at',NULL)->get();
            }
            else if( $document_type_exist == true && $req->prefixtags == "Sales Order")
            {
              $data = Document::where('document_type',$req->prefixtags)->whereBetween('sales_order_date',[$form,$to])->where('deleted_at',NULL)->get();
            }
            else if($document_type_exist == true && $req->prefixtags == "Shipping Bill")
            {
              $data = Document::where('document_type',$req->prefixtags)->whereBetween('shippingbill_date',[$form,$to])->where('deleted_at',NULL)->get();
            }
            else
            {
               $data = "";
            }

        }
        else if($req->form_date !==null  && $req->to_date !==null)
        {
           $data = Document::whereBetween('invoice_date',[$form,$to])->orWhereBetween('sales_order_date',[$form,$to])->orWhereBetween('shippingbill_date',[$form,$to])->where('deleted_at',NULL)->get();
         // dd($data);
        }
        else
        {
            $data = "";
        }
        return $data;
       }    
      
    }
   
   

}
