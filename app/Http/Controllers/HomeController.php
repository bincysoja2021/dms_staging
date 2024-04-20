<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Userlogs;
use App\Models\Passwordhistroy;
use App\Models\Notification;
use App\Models\Document;

class HomeController extends Controller
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
   Date        : 01/03/2024
   Description :  login form
**********************************/    
     public function login_form()
    {
        return view('auth.login');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $session_role=session()->put('user_role', Auth::user()->user_type);
        User::where('id',Auth::user()->id)->update(['last_login_time'=>date("d-m-Y h:i:sa")]);
        Userlogs::create(['user_id'=>Auth::user()->id,'last_login_time'=>date("d-m-Y h:i:sa"),'login_ip'=>request()->ip()]);
        $Passwordhistroy=Passwordhistroy::where('user_id', Auth::user()->id)->first();
        $check_exists=Passwordhistroy::where('user_id', Auth::user()->id)->exists();
        if( $check_exists== "true" && $Passwordhistroy->password_old == Null && $Passwordhistroy->user_type=="Manager")
        {
           return view('admin.manager.reset_password');
        }
        else
        {
            $notification=Notification::count();
            $shipping_bills=Document::where('document_type','Shipping Bill')->where('deleted_at',NULL)->count();
            $sales_order=Document::where('document_type','Sales Order')->where('deleted_at',NULL,)->count();
            $invoices=Document::where('document_type','Invoice')->where('deleted_at',NULL,)->count();
            $all_documents=Document::where('deleted_at',NULL,)->count();
            $upload_doc=Document::where('deleted_at',NULL,)->where('status','Success')->take(3)->get();
            $failed_doc=Document::where('deleted_at',NULL,)->where('status','Failed')->take(3)->get();
            return view('admin.dashboard',compact('notification','shipping_bills','sales_order','invoices','all_documents','upload_doc','failed_doc'));
        }
    }
/******************************
   Date        : 27/02/2024
   Description :  logout
******************************/
     public function logout()
    {
        session()->forget('user_role');
        Auth::logout();
        return redirect()->route('form_login')->with('message','User logout Successfully!');

    }

}
