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
use Validator;

class Invoicecontroller extends Controller
{
    public $successStatus = 200; 
    public function insert_Data(Request $req)
    {
        $validator = Validator::make($req->all(), [
        'invoice_id'=> 'required',
        'invoice_date'  => 'required'
        ]);
        if ($validator->fails())
        {
        $errors  = json_decode($validator->errors());
        $invoice_id=isset($errors->invoice_id[0])? $errors->invoice_id[0] : '';
        $invoice_date=isset($errors->invoice_date[0])? $errors->invoice_date[0] : '';
        if($invoice_id)
        {
        $msg = $invoice_id;
        }
        else if($invoice_date)
        {
        $msg = $invoice_date;
        }
        return response()->json(['message' =>$validator->errors(),'statusCode'=>422,'data'=>[],'success'=>'error'],200);
        }
        $check_exist=Invoicedate::where('invoice_id',$req->invoice_id)->exists();
        
        if($check_exist===true)
        {
            return response()->json(['message'=>"Invoice date exist to the server.", 'data'=>[],'statusCode' => $this-> successStatus], $this-> successStatus);   
        }
        else
        {

            $data=Invoicedate::Create(['invoice_id'=>$req->invoice_id,'invoice_date'=>$req->invoice_date]);
            return response()->json(['message'=>"Invoice date added Successfully.", 'data'=>$data,'statusCode' => $this-> successStatus], $this-> successStatus);
        }
        
        
    }

}    