<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Redirect;
use Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Auth;
use Session;
use App\Models\Passwordhistroy;

class Usercontoller extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
/**********************************
   Date        : 05/03/2024
   Description :  view user
**********************************/
    public function view_user()
    {
      return view('admin.view_user');
    }
/****************************************
   Date        : 07/03/2024
   Description :  reset user password
****************************************/
    public function reset_user_password()
    {
      return view('admin.manager.reset_password');
    }

/**********************************
   Date        : 05/03/2024
   Description :  list for users
**********************************/    
    public function getusers(Request $request)
    {
      if ($request->ajax()) {
          $data = User::where('deleted_at',NULL)->latest()->get();
          return Datatables::of($data)
              ->addIndexColumn()
              ->addColumn('action', function($row){
                  $actionBtn = '<a href="' . route('view.users', $row->id) .'"><i class="fa fa-eye"  aria-hidden="true"></i></a>
                                <a href="' . route('edit.users', $row->id) .'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <a   onclick="delete_user_modal('.$row->id.')" ><i class="fa fa-trash" aria-hidden="true"></i></a>';
                  return $actionBtn;
              })
               ->addColumn('checkbox', function ($item) {
              $actionBtn ='<input type="checkbox" name="item_checkbox[]" value="' . $item->id . '">';
              return $actionBtn;
              })
              ->rawColumns(['checkbox','action'])
              ->make(true);
      }
    }
/**********************************
   Date        : 05/03/2024
   Description :  edut users
**********************************/
    public function edit_user()
    {
      return view('admin.edit_user');
    }
/**********************************
   Date        : 05/03/2024
   Description :  update users
**********************************/
    public function update_user(Request $req)
    { 
        $validatedData = $req->validate([
          'email' => 'required|email|email:rfc,dns|max:255',
          'full_name'=>'required|max:100',
          'office'=>'required',
          'department_section'=>'required'
      ], [
          'full_name.required' => 'Please enter the name.',
          'office.required' => 'Please enter the office.',
          'email.required' => 'Please enter the email.',
          'department_section.required' => 'Please enter the Department.',
      ]);
        User::where('id',$req->id)->update([
          'email'=>$req->email,
          'full_name'=>$req->full_name,
          'user_name'=>$req->full_name,
          'office'=>$req->office,
          'department_section'=>$req->department_section
        ]);
        return redirect()->route('all_users')->with('message','User updated Successfully!');
    }    
/**********************************
   Date        :29/02/2024
   Description :  list settings
**********************************/
    public function settings()
    {
      return view('admin.settings');
    }
/**********************************
   Date        : 29/02/2024
   Description :  edit profile
**********************************/
     public function edit_profile($id)
    {
      $user=User::where('id',$id)->first();
      return view('admin.edit_profile',compact('user'));
    }
/**********************************
   Date        : 29/02/2024
   Description :  update profile
**********************************/
    public function update_profile(Request $req)
    { 
        $validatedData = $req->validate([
          'full_name' => 'required|max:255',
          'email' => 'required|email|email:rfc,dns|max:255'
      ], [
          'full_name.required' => 'Please enter the user name.',
          'email.required' => 'Please enter the email.',
      ]);
        User::where('id',$req->id)->update([
          'full_name'=>$req->full_name,
          'email'=>$req->email
        ]);
        return redirect()->route('settings')->with('message','User updated Successfully!');

    }
     public function tags()
    {
      return view('admin.tags');
    }
     public function all_users()
    {
      return view('admin.user.all_users');
    }
     public function add_users()
    {
      return view('admin.user.add_users');
    }
/**********************************
   Date        : 05/03/2024
   Description :  view users
**********************************/      
     public function view_users($id)
    {
      $data=User::where('id',$id)->first();
      return view('admin.user.view_user',compact('data'));
    }
/**********************************
   Date        : 05/03/2024
   Description :  edit users
**********************************/      
     public function edit_users($id)
    {
      $data=User::where('id',$id)->first();
      return view('admin.user.edit_user',compact('data'));
    }
/**********************************
   Date        : 04/03/2024
   Description :  add users
**********************************/    
    public function submit(Request $req)
    {
      $validatedData = $req->validate([
          'email' => 'required|email|email:rfc,dns|max:255|unique:users',
          'user_name' => 'required|max:255',
          'office' => 'required',
          'user_type' => 'required',
          'department_section' => 'required',

      ], [
          'email.required' => 'Please enter the email.',
          'user_name.required' => 'Please enter the user name.',
          'office.required' => 'Please enter the office.',
          'user_type.required' => 'Please enter the type.',
          'department_section.required' => 'Please enter the department.',
      ]);
      $random_password=Str::random(6);
      $userData=User::create([
          'email'=>$req->email,
          'employee_id'=>random_int(100000, 999999),
          'full_name'=>$req->user_name,
          'user_name'=>$req->user_name,
          'user_type'=>$req->user_type,
          'office'=>$req->office,
          'department_section'=>$req->department_section,
          'active_status'=>1,
          'user_registerd_date'=>date("Y-m-d"),
          'password'=>Hash::make($random_password),
         ]); 
      $user_details=User::where('email',$req->email)->first();
      $details = [
        'type' => 'Password',
        'customer_details' => $user_details,
        'user_name'=>$req->user_name,
        'random_password'=>$random_password,
        'email'=>$user_details->email,
        'host'=> $_SERVER['HTTP_HOST'],
        'subject' => "Your registerd  Password !",
      ];
      $response=send_otp_to_email($req->email,$details,$blade="admin.email.send_password",$subject="Generated password",$user_details->user_name);
      Passwordhistroy::create([
            'added_by'=>Auth::user()->id,
            'user_id'=>$userData->id,
            'password_new'=>$random_password,
            'user_type'=>$req->user_type,
            'password_new_date'=>date("Y-m-d")
      ]);

      return redirect()->route('all_users')->with('message','User Added Successfully!');
    }
/**********************************
   Date        : 05/03/2024
   Description :  delete users
**********************************/
     public function user_deactivate($id)
    {
        User::find($id)->update(['active_status'=>0]);
        return back();
    }
/***************************************
   Date        : 05/03/2024
   Description :  delete users
***************************************/    
    public function delete_users($id)
    {
        $msg=User::where('id',$id)->delete();
        return redirect()->route('all_users')->with('message','User deleted Successfully!');
    }    
/***************************************
   Date        : 04/03/2024
   Description :  delete users
***************************************/    
    public function delete_multi_users(Request $request)
    {
        $ids = $request->input('ids');
        User::whereIn('id', $ids)->delete();
        return response()->json(['success' => true]);
    }  
/*******************************************
   Date        : 07/03/2024
   Description : Manager Password reset submission
*******************************************/    
    public function manager_reset_password_submit(Request $req)
    {
      $validatedData = $req->validate([
          'password' => 'required|min:6|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
          'password-confirm' => 'required_with:password|same:password|min:6'
      ], [
          'password.required' => 'Please enter the atleast one Capital letter,small letter,numbers and special letters.',
          'password-confirm.required' => 'Please enter the same password.',
      ]);
      User::where('id',Auth::user()->id)->update(['password'=>Hash::make($req->password)]);
      $Passwordhistroy=Passwordhistroy::where('user_id',Auth::user()->id)->first();
      Passwordhistroy::where('user_id',Auth::user()->id)->update(['password_new'=>$req->password,'password_old'=>$Passwordhistroy->password_new]);
      session()->forget('user_role');
      Auth::logout();
      return redirect('/')->with('message','Succssfully updated the password!');
    }        

}
