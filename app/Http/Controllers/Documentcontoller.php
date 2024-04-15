<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Document;
use App\Models\Notification;
use Yajra\DataTables\DataTables;
use Storage;

class Documentcontoller extends Controller
{
    
    public function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('auth');
    }

    public function all_document()
    {
      return view('admin.document.all_document');
    }

    public function view_file($id)
    {
      $doc=Document::where('id',$id)->first();
      return view('admin.document.view_file',compact('doc'));
    }
    public function edit_file($id)
    {
      $doc=Document::where('id',$id)->first();
      return view('admin.document.edit_file',compact('doc'));
    }
/**********************************
   Date        : 13/03/2024
   Description :  Document update
**********************************/
    public function document_update(Request $req)
    { 
        Document::where('id',$req->id)->update([
          'invoice_number'=>$req->invoice_number,
          'invoice_date'=>$req->invoice_date,
          'sales_order_number'=>$req->sales_order_number,
          'shipping_bill_number'=>$req->shipping_bill_number,
          'company_name'=>$req->company_name,
          'company_id'=>$req->company_id,
        ]);
        return redirect()->route('all_document')->with('message','Document updated Successfully!');
    }
/***************************************
   Date        : 13/03/2024
   Description :  delete docs
***************************************/    
    public function delete_docs($id)
    {
        $data=Document::where('id',$id)->first();
        Storage::disk('ftp')->delete($data->filename);
        Storage::disk('ftp')->delete($data->thumbnail);
        $msg=Document::where('id',$id)->delete();
        return redirect()->route('all_document')->with('message','Document has been deleted Successfully!');
    }    
/**************************************************
   Date        : 13/03/2024
   Description :  delete multiple documents
***************************************************/    
    public function delete_multi_docs(Request $request)
    {
        $ids = $request->input('ids');
        Document::whereIn('id', $ids)->delete();
        return response()->json(['success' => true]);
    }  
/**************************************************
   Date        : 01/04/2024
   Description :  download pdf
***************************************************/    
    public function download($filename)
    {
      $check=Storage::disk('ftp')->exists($filename);
      //download images to ftp
      if ($check==true) 
      {
        return Storage::disk('ftp')->download($filename);
      }
    }
/**************************************************
   Date        : 09/04/2024
   Description :  load image view page
***************************************************/     
    public function  load_images($file)
    {
      $imageData= Storage::disk('ftp')->get($file);
      return response($imageData, 200)->header('Content-Type', 'image/jpeg');

    }  
/**************************************************
   Date        : 09/04/2024
   Description :  upload now
***************************************************/     
    public function  upload_now(Request $req,$id)
    {
      dd($req);
      $data=Document::where('id',$id)->first();
    }                   
    public function all_invoices()
    {
      return view('admin.invoice.all_invoices');
    }

    public function view_invoice($id)
    {
      $doc=Document::where('id',$id)->first();
      return view('admin.invoice.view_invoice',compact('doc'));
    }
    public function edit_invoice($id)
    {
      $doc=Document::where('id',$id)->first();
      return view('admin.invoice.edit_invoice',compact('doc'));
    }
/*******************************************
   Date        : 13/03/2024
   Description :  Invoice Document update
*******************************************/
    public function invoice_document_update(Request $req)
    { 
        Document::where('id',$req->id)->update([
          'invoice_number'=>$req->invoice_number,
          'invoice_date'=>$req->invoice_date,
          'company_name'=>$req->company_name,
          'company_id'=>$req->company_id,
        ]);
        return redirect()->route('all_invoices')->with('message','Invoice Document updated Successfully!');

    }    
/***************************************
   Date        : 13/03/2024
   Description :  delete invoice docs
***************************************/    
    public function delete_invoice($id)
    {
        $msg=Document::where('id',$id)->delete();
        return redirect()->route('all_invoices')->with('message','Invoice Document has been deleted Successfully!');

    }    
/**************************************************
   Date        : 13/03/2024
   Description :  delete multiple invoice docs
***************************************************/    
    public function delete_multi_invoice(Request $request)
    {
        $ids = $request->input('ids');
        Document::whereIn('id', $ids)->delete();
        return response()->json(['success' => true]);
    }    

    public function schedule_document($id)
    {
      $document_id=Document::where('id', $id)->first();
      return view('admin.schedule_document',compact('document_id'));
    }
    public function upload_document()
    {
      return view('admin.upload_document');
    }
    public function failed_document()
    {
      return view('admin.failed_document');
    }
/*********************************************
   Date        : 12/03/2024
   Description :  Manual docs submission
*********************************************/     
    public function submit(Request $req)
    {
      $validatedData = $req->validate([
          'doc_type'=>'required',
          'company_id'=>'required',
          'company_name'=>'required',
          'image'=>'required|mimes:pdf|file|max:2048'
      ], [
          'doc_type.required' => 'Please enter the Document type.',
          'company_id.required' => 'Please enter the Company id.',
          'company_name.required' => 'Please enter the Company name.',
          'image.required' => 'Please upload the file (format contain pdf,maximum size upto 2mb).',
      ]);
      $genearte_number=random_int(100000, 999999);
      $check_exist=Document::where('user_id',Auth::user()->id)->where('date',date('d-m-y'))->where('document_type',$req->doc_type)->where('company_name',$req->company_name)->where('company_id',$req->company_id)->exists();
      if($check_exist==true)
      {
        $doc_id=Document::where('user_id',Auth::user()->id)->where('date',date('d-m-y'))->where('document_type',$req->doc_type)->where('company_name',$req->company_name)->where('company_id',$req->company_id)->first();
        $doc_id->update([
        'user_id'=>Auth::user()->id,
        'date'=>date('d-m-y'),
        'document_type'=>$req->doc_type,
        'invoice_number'=>'IN-'.$genearte_number,
        'doc_id'=>'DOC-'.$genearte_number,
        'invoice_date'=>date('d-m-y'),
        'sales_order_number'=>'SO-'.$genearte_number,
        'shipping_bill_number'=>'SB-'.$genearte_number,
        'company_name'=>$req->company_name,
        'company_id'=>$req->company_id,
        'status'=>"Success",
        'user_name'=>Auth::user()->user_name
        ]);
      }
      return redirect()->route('upload_document')->with('message','Manualy upload file Successfully!');

    }

/*********************************************
   Date        : 12/03/2024
   Description :  Manual docs submission
*********************************************/     
    public function pdf_to_thubnail_docs(Request $req)
    {
      $genearte_number=random_int(100000, 999999);
      $file_upload_returns=ftp_upload_docs($req->hasFile('document_file'),$req->file('document_file'));
      //thumbnail
      $ftp_thumbnail_upload_docs=ftp_thumbnail_upload_docs($req->hasFile('thumbnail'),$req->file('thumbnail'));
      $Document= Document::Create([
          'user_id'=>Auth::user()->id,
          'date'=>date('d-m-y'),
          'document_type'=>$req->doc_type,
          'invoice_number'=>'IN-'.$genearte_number,
          'doc_id'=>'DOC-'.$genearte_number,
          'invoice_date'=>date('d-m-y'),
          'sales_order_number'=>'SO-'.$genearte_number,
          'shipping_bill_number'=>'SB-'.$genearte_number,
          'company_name'=>$req->company_name,
          'company_id'=>$req->company_id,
          'filename'=> $file_upload_returns,
          'thumbnail'=>$ftp_thumbnail_upload_docs,
          'status'=>"Success",
          'user_name'=>Auth::user()->user_name
        ]);

      notification_data($id=Auth::user()->id,$type=Auth::user()->user_type,$date=date('d-m-y'),$message="Manualy upload file Successfully",$message_title="Manualy Document upload",$status="Completed",$doc_id=$Document->id);
      return redirect()->route('upload_document')->with('message','Manualy upload file Successfully!');

    }

/*********************************************************
   Date        : 12/03/2024
   Description :  manual docs failed doc submission
*********************************************************/     
    public function failed_docs_submission(Request $req)
    {
     
      $genearte_number=random_int(100000, 999999);
      $file_upload_returns=ftp_upload_docs($req->hasFile('document_file'),$req->file('document_file'));
      //thumbnail
      $ftp_thumbnail_upload_docs=ftp_thumbnail_upload_docs($req->hasFile('thumbnail'),$req->file('thumbnail'));
      $Document=Document::Create([
          'user_id'=>Auth::user()->id,
          'date'=>date('d-m-y'),
          'document_type'=>$req->doc_type,
          'invoice_number'=>'IN-'.$genearte_number,
          'doc_id'=>'DOC-'.$genearte_number,
          'invoice_date'=>date('d-m-y'),
          'sales_order_number'=>'SO-'.$genearte_number,
          'shipping_bill_number'=>'SB-'.$genearte_number,
          'company_name'=>$req->company_name,
          'company_id'=>$req->company_id,
          'filename'=>$file_upload_returns,
          'thumbnail'=>isset($ftp_thumbnail_upload_docs) ? $ftp_thumbnail_upload_docs : $file_upload_returns,
          'status'=>"Failed",
          'user_name'=>Auth::user()->user_name
        ]);
      notification_data($id=Auth::user()->id,$type=Auth::user()->user_type,$date=date('d-m-y'),$message="Manualy Document upload",$message_title=$req->msg,$status="Failed",$doc_id=$Document->id);
      return response()->json([
          'message'   =>$req->msg,
          'success'   => 1,
        ]);
    }
/**********************************
   Date        : 13/03/2024
   Description :  list for docs
**********************************/    
    public function getdoc(Request $request)
    {
      if ($request->ajax()) {
          $data = Document::where('deleted_at',NULL)->latest()->get();
          return Datatables::of($data)
              ->addIndexColumn()
              ->addColumn('action', function($row)
              {
              if(Auth::user()->user_type=="Super admin")
               {
                $actionBtn = '<a href="' . route('view_file', $row->id) .'"><i class="fa fa-eye"  aria-hidden="true"></i></a>
                              <a href="' . route('edit_file', $row->id) .'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                              <a   onclick="delete_doc_modal('.$row->id.')" ><i class="fa fa-trash" aria-hidden="true"></i></a>
                              <a href="'.route('download.pdf', $row->filename).'"><i class="fa fa-download" aria-hidden="true"></i></a>';
                return $actionBtn;
              }
              })
              ->addColumn('checkbox', function ($item) {
              $actionBtn ='<input type="checkbox" name="item_checkbox[]" value="' . $item->id . '">';
              return $actionBtn;
              })
              ->addColumn('thumbnail', function ($row) {
              $actionBtn ="<button class='view_image' data-toggle='modal' data-target='#pdfModal' data-image='".route('load_images', $row->thumbnail)."'><img src='".route('load_images', $row->thumbnail)."'  width='100px' height='100px' >
              </button>";
              return $actionBtn;
              })
              ->rawColumns(['thumbnail','checkbox','action'])
              ->make(true);
        }
    }
/********************************************
   Date        : 13/03/2024
   Description :  list for all invoice docs
********************************************/    
    public function getallinvoice(Request $request)
    {
      if ($request->ajax()) {
          $data = Document::where('document_type',"Invoice")->where('deleted_at',NULL)->latest()->get();
          return Datatables::of($data)
              ->addIndexColumn()
              ->addColumn('action', function($row){
                if(Auth::user()->user_type=="Super admin")
                {
                  $actionBtn = '<a href="' . route('view_invoice', $row->id) .'"><i class="fa fa-eye"  aria-hidden="true"></i></a>
                                <a href="' . route('edit_invoice', $row->id) .'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <a   onclick="delete_invoice_modal('.$row->id.')" ><i class="fa fa-trash" aria-hidden="true"></i></a>
                                <a   href="'.route('download.pdf', $row->filename).'"><i class="fa fa-download" aria-hidden="true"></i></a>';
                  return $actionBtn;
                }
              })
               ->addColumn('checkbox', function ($item) {
              $actionBtn ='<input type="checkbox" name="item_checkbox[]" value="' . $item->id . '">';
              return $actionBtn;
              })
               ->addColumn('thumbnail', function ($row) {
              $thumbnailpath = asset('thumbnail_uploads/' . $row->thumbnail);
              $actionBtn ="<button class='view_image' data-toggle='modal' data-target='#pdfinvoiceModal' data-image='".route('load_images', $row->thumbnail)."'><img src='".route('load_images', $row->thumbnail)."' width='100px' height='100px' >
              </button>";

              return $actionBtn;
              })
              ->rawColumns(['thumbnail','checkbox','action'])
              ->make(true);
      }
    }
/********************************************
   Date        : 13/03/2024
   Description :  list for all failed  docs
********************************************/    
    public function get_failed_doc_list(Request $request)
    {
      if ($request->ajax()) {
          $data = Document::where('status',"Failed")->where('deleted_at',NULL)->latest()->get();
          return Datatables::of($data)
              ->addIndexColumn()
              ->addColumn('action', function($row){
                if(Auth::user()->user_type=="Super admin")
                {
                  $actionBtn = '<form enctype="multipart/form-data"><a href="" class="btn btn-primary btn-sm btn-upload" >
                  Upload Now<input type="file" name="image" id="image"><i class="fa fa-upload" aria-hidden="true"></i>
                  </a>&nbsp;
                  <a href="' . route('schedule_document', $row->id) .'" class="btn btn-primary btn-sm">
                  Reschedule <i class="fa fa-repeat" aria-hidden="true"></i>
                  </a>&nbsp;<a   onclick="delete_faileddoc_modal('.$row->id.')" ><i class="fa fa-trash" aria-hidden="true"></i></a></form>';
                  return $actionBtn;
                }
              })
               ->addColumn('checkbox', function ($item) {
              $actionBtn ='<input type="checkbox" name="item_checkbox[]" value="' . $item->id . '">';
              return $actionBtn;
              })
               ->addColumn('thumbnail', function ($row) {
              $actionBtn ="<img src='".route('load_images', $row->thumbnail)."' width='100px' height='100px' >";

              return $actionBtn;
              })
              ->rawColumns(['thumbnail','checkbox','action'])
              ->make(true);
      }
    }
/***************************************
   Date        : 13/03/2024
   Description :  delete failed docs
***************************************/    
    public function delete_failed_docs($id)
    {
        $msg=Document::where('id',$id)->delete();
        return redirect()->route('all_invoices')->with('message','Failed Document has been deleted Successfully!');

    }    
/**************************************************
   Date        : 13/03/2024
   Description :  delete multiple failed docs
***************************************************/    
    public function delete_multi_failed_docs(Request $request)
    {
        $ids = $request->input('ids');
        Document::whereIn('id', $ids)->delete();
        return response()->json(['success' => true]);
   
    }          
}
