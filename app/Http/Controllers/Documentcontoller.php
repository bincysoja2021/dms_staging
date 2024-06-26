<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Document;
use App\Models\Auto_scheduleDocument;
use App\Models\Notification;
use Yajra\DataTables\DataTables;
use Storage;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\File;
use App\Models\Invoicedate;

class Documentcontoller extends Controller
{
    public $successStatus = 200; 
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
        if (Storage::disk('ftp')->exists($file)) 
        {
          $imageData = Storage::disk('ftp')->get($file);
          return response($imageData, 200)->header('Content-Type', 'image/jpeg');
        } 
        else 
        {
          $defaultImagePath = public_path('images/Noimage.png');
          $imageData = file_get_contents($defaultImagePath);
          return response($imageData, 200)->header('Content-Type', 'image/png');
        }

    }  
/**************************************************
   Date        : 09/04/2024
   Description :  upload now
***************************************************/     
    public function  upload_now(Request $req,$id)
    {
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
      if($id==0)
      {
        return view('admin.auto_schedule_document');   
      }
      else
      {
        $document_id=Document::where('id', $id)->first();
        return view('admin.schedule_document',compact('document_id'));
      }
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
      $check_exist=Invoicedate::where('invoice_id',$Document->invoice_number)->exists();
      if($check_exist==true)
      {
        $submit_invoice_date=Invoicedate::where('invoice_id',$Document->invoice_number)->first();
        $doc_data=Document::where('id',$Document->id)->update(['invoice_date'=>$submit_invoice_date->invoice_date]);
      }
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
          $data = Document::where('deleted_at',NULL)->orderBy('id',"DESC")->latest()->get();
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
              ->addColumn('date', function ($item) {
              $actionBtn =date('d-m-Y',strtotime($item->date));
              return $actionBtn;
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
              ->rawColumns(['date','thumbnail','checkbox','action'])
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
          $data = Document::where('document_type',"Invoice")->where('deleted_at',NULL)->orderBy('id',"DESC")->latest()->get();
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
              ->addColumn('date', function ($item) {
              $actionBtn =date('d-m-Y',strtotime($item->date));
              return $actionBtn;
              })
               ->addColumn('thumbnail', function ($row) {
              $thumbnailpath = asset('thumbnail_uploads/' . $row->thumbnail);
              $actionBtn ="<button class='view_image' data-toggle='modal' data-target='#pdfinvoiceModal' data-image='".route('load_images', $row->thumbnail)."'><img src='".route('load_images', $row->thumbnail)."' width='100px' height='100px' >
              </button>";
              return $actionBtn;
              })
              ->rawColumns(['date','thumbnail','checkbox','action'])
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
          $data = Document::where('status',"Failed")->where('deleted_at',NULL)->orderBy('id',"DESC")->latest()->get();
          return Datatables::of($data)
              ->addIndexColumn()
              ->addColumn('action', function($row){
                if(Auth::user()->user_type=="Super admin")
                {
                  $actionBtn = '<form enctype="multipart/form-data"><a href="" class="btn btn-primary btn-sm btn-upload" >
                  Rescheduled upload files<input type="hidden" name="failed_doc_id" id="failed_doc_id" class="failed_doc_id" value="'. $row->id.'"><input type="file" name="image" id="image"><i class="fa fa-upload" aria-hidden="true"></i>
                  </a>&nbsp;
                  <a   onclick="delete_faileddoc_modal('.$row->id.')" ><i class="fa fa-trash" aria-hidden="true"></i></a></form>';
                  return $actionBtn;
                }
              })
              ->addColumn('date', function ($item) {
              $actionBtn =date('d-m-Y',strtotime($item->date));
              return $actionBtn;
              })
               ->addColumn('checkbox', function ($item) {
              $actionBtn ='<input type="checkbox" name="item_checkbox[]" value="' . $item->id . '">';
              return $actionBtn;
              })
               ->addColumn('thumbnail', function ($row) {
              $actionBtn ="<img src='".route('load_images', $row->thumbnail)."' width='100px' height='100px' >";

              return $actionBtn;
              })
              ->rawColumns(['date','thumbnail','checkbox','action'])
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
/*******************************************************
   Date        : 16/03/2024
   Description :  failed document re upload  docs
********************************************************/  
    public function failed_document_re_upload_docs(Request $req)
    {
      $path = $req->file('document_file')->store('failed_document_reupload');
      $file = $req->file('document_file');
      $reschedule_docs_fileName = $file->getClientOriginalName();
      $file->move(public_path('failed_document_reupload'), $reschedule_docs_fileName);
      //thumbnail
      $path_store = $req->file('thumbnail')->store('failed_thumbnail_document_reupload');
      $file = $req->file('thumbnail');
      $reschedule_thumbnail_docs_fileName = $file->getClientOriginalName();
      $file->move(public_path('failed_thumbnail_document_reupload'), $reschedule_thumbnail_docs_fileName);
      $docData=Document::where('id',$req->id)->first();
      $check_exist=Invoicedate::where('invoice_id',$docData->invoice_number)->exists();
      if($check_exist==true)
      {
          Document::where('id',$req->id)->update(['reschedule_docs'=>$reschedule_docs_fileName,'reschedule_thumbnail_docs'=>$reschedule_thumbnail_docs_fileName]);
           return response()->json([
            'success'   => 1,
          ]);
      }
      else
      {
          Document::where('id',$req->id)->update(['reschedule_docs'=>$reschedule_docs_fileName,'reschedule_thumbnail_docs'=>$reschedule_thumbnail_docs_fileName,'status'=>"Failed"]);
          notification_data($id="1",$type="Admin",$date=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y'),$message="Invoice number does not exists.",$message_title="Invoice number does not exists.",$status="Failed",$doc_id=$req->id);
          return response()->json([
            'success'   => 0,
          ]);
      }
    } 
/**************************************************
   Date        : 25/04/2024
   Description :  Time schedule documents
***************************************************/  
    public function time_scheduled_docs(Request $req)
    {
      Document::where('id',$req->id)->update(['start_date'=>Carbon::parse($req->date)->format('d-m-Y'),'time'=>$req->time,'status'=>"Active"]);
      return redirect('/all_document')->with('message','Scheduled documents Successfully!');

    } 
/**************************************************
   Date        : 25/04/2024
   Description :  Pre time scheduled documents
***************************************************/     
    public function pre_time_scheduled_docs(Request $req)
    {
       Document::where('id',$req->id)->update(['start_date'=>Carbon::parse($req->start_date)->format('d-m-Y'),'end_date'=>Carbon::parse($req->end_date)->format('d-m-Y'),'time'=>$req->time]);
       return redirect('/failed_document')->with('message','Scheduled documents Successfully!');
    }   


    public function auto_time_scheduled_docs(Request $req)
    {
      $checkexist=Auto_scheduleDocument::where('start_date',Carbon::parse($req->date)->format('d-m-Y'))->where('time',$req->time)->exists();
      if($checkexist==true)
      {
        return redirect('/schedule_document/0')->with('message','Already scheduled auto scheduled documents!');
      }
      else
      {
        Auto_scheduleDocument::create(['start_date'=>Carbon::parse($req->date)->format('d-m-Y'),'time'=>$req->time,'today_date'=>Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y'),'status'=>"Active"]);
        return redirect('/scheduled_list')->with('message','Auto scheduled documents Successfully!');
      }


    } 
    public function auto_pre_time_scheduled_docs(Request $req)
    {
       $checkexist=Auto_scheduleDocument::where('start_date',Carbon::parse($req->start_date)->format('d-m-Y'))->where('end_date',Carbon::parse($req->end_date)->format('d-m-Y'))->where('time',$req->time)->exists();
      if($checkexist==true)
      {
        return redirect('/schedule_document/0')->with('message','Already pre scheduled docs to be setting!');
      }
      else
      {
        Auto_scheduleDocument::create(['start_date'=>Carbon::parse($req->start_date)->format('d-m-Y'),'end_date'=>Carbon::parse($req->end_date)->format('d-m-Y'),'time'=>$req->time,'today_date'=>Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y')]);
        return redirect('/scheduled_list')->with('message','Auto scheduled documents Successfully!');
      }
    }

    public function scheduled_list()
    {
      $auto_schedule_document_inactive=Auto_scheduleDocument::where('status','Inactive')->orderBy('id','DESC')->paginate(10);
      $auto_schedule_document_active=Auto_scheduleDocument::where('status','Active')->orderBy('id','DESC')->paginate(10);
      return view('admin.auto_schedule_document_list',compact('auto_schedule_document_inactive','auto_schedule_document_active'));
    }

             
}
