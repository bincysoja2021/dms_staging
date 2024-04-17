<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Tag;
use App\Models\Document;

class Tagcontoller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set("Asia/Kolkata");
        $this->middleware('auth');
    }
/**********************************
   Date        : 17/04/2024
   Description :  submit tags
**********************************/    
     public function submit_tags(Request $req)
    { 
        if($req->id==null)
        {
            $data=Tag::create(['tag_name'=>$req->tag_name]);
            return redirect()->route('tags')->with('message','New tag created Successfully!');
        }
        else
        {
            $data=Tag::where('id',$req->id)->update(['tag_name'=>$req->tag_name]);
            return redirect()->route('tags')->with('message','Tag updated Successfully!');
        }

    }
/**********************************
   Date        : 17/04/2024
   Description :  edit tags
**********************************/    
     public function edit_tags($id)
    { 
        $data=Tag::where('id',$id)->first();
        $tag_data=Tag::where('deleted_at',Null)->get();
        return view('admin.tags',compact('data','tag_data'));

    }
/***************************************
   Date        : 17/04/2024
   Description :  delete tags
***************************************/    
    public function delete_tags($id)
    {
        $data=Tag::where('id',$id)->delete();
        return redirect()->route('tags')->with('message','Tag deleted Successfully!');
    } 
/***************************************
   Date        : 17/04/2024
   Description :  tag search
***************************************/    
    public function tags_search(Request $req)
    {
        $tag_data=Tag::where('id',$req->id)->first();
        // dd($tag_data->tag_name);
        if($tag_data->tag_name=="Invoice Number")
        {
            $data=Document::whereNotNull('date')->get();
        }
        else if($tag_data->tag_name=="Invoice Date")
        {
            $data=Document::whereNotNull('date')->get();

        }
        else if($tag_data->tag_name=="Sales Order Number")
        {
            $data=Document::whereNotNull('sales_order_number')->get();

        }
        else if($tag_data->tag_name=="Shipping Bill Number")
        {
            $data=Document::whereNotNull('shipping_bill_number')->get();

        }
        else if($tag_data->tag_name=="Company Name")
        {
            $data=Document::whereNotNull('company_name')->get();

        }
        else if($tag_data->tag_name=="Company ID")
        {
            $data=Document::whereNotNull('company_id')->get();

        }
        else if($tag_data->tag_name=="File name")
        {
            $data=Document::whereNotNull('filename')->get();

        }
        else if($tag_data->tag_name=="Uploaded date")
        {
            $data=Document::whereNotNull('date')->get();

        }
        else
        {
           $data="";
        }
        return $data;
    }     
    
}
