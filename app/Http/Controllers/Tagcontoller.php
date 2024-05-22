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
        $tag_data=Tag::where('tag_name',$req->tag_name)->exists();

        if($req->id==null)
        {
            if($tag_data==true)
            {
                return redirect()->route('tags')->with('message','Already tag has been submited!');
            }
            else
            {
                $data=Tag::create(['tag_name'=>$req->tag_name]);
                return redirect()->route('tags')->with('message','New tag created Successfully!');
            }
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
        $tag_data = Tag::where('id', $req->id)->first();

        $data = null;

        if ($tag_data) 
        {
            $query = Document::query();

            // Add conditions based on tag name
            if ($tag_data->tag_name == "Invoice Number") 
            {
                $query->whereNotNull('date');
            } 
            elseif ($tag_data->tag_name == "Invoice Date") 
            {
                $query->whereNotNull('date');
            } 
            elseif ($tag_data->tag_name == "Sales Order Number") 
            {
                $query->whereNotNull('sales_order_number');
            } 
            elseif ($tag_data->tag_name == "Shipping Bill Number") 
            {
             $query->whereNotNull('shipping_bill_number');
            } 
            elseif ($tag_data->tag_name == "Company Name") 
            {
                $query->whereNotNull('company_name');
            } 
            elseif ($tag_data->tag_name == "Company ID") 
            {
                $query->whereNotNull('company_id');
            } 
            elseif ($tag_data->tag_name == "File name") 
            {
                $query->whereNotNull('filename');
            } 
            elseif ($tag_data->tag_name == "Uploaded date") 
            {
                $query->whereNotNull('date');
            }
            // Get paginated results
            $data = $query->paginate(10); // Adjust the number per page as needed
        }
        return response()->json($data);
    }
    
    
}
