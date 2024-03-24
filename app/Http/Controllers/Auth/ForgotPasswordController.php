<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Redirect;
use Session;
use Hash;
use Auth;
use App\Models\Passwordhistroy;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
/**********************************
   Date        : 01/03/2024
   Description :  OTP Submit
**********************************/
    public function otp_submit(Request $request)
    {
      $this->validate($request,
      [
          'otp'=>['required', 'integer','min:6'],
      ]);
      $id = Cookie::get('user_id');
      $check_exist=User::where('id',$id)->exists();
      $exist_otp=User::where('otp',$request->otp)->exists();
      if($check_exist==true && $exist_otp)
      {
        return redirect('/reset_password');
      }
      else
      {
        Session::flash('message', ['text'=>'OTP has invalid.!','type'=>'danger']);
        return redirect('/forgot_password_otp');
      }
    }
/**********************************
   Date        : 01/03/2024
   Description :  forgot password
**********************************/
    public function forgot_password()
    {
      return view('admin.password_reset.forgot_password');
    }

/*****************************************
   Date        : 01/03/2024
   Description :  otp send to the email
*****************************************/    
    public function forgot_password_otp()
    {
      return view('admin.password_reset.forgot_password_otp');
    }
/**************************************
   Date        : 01/03/2024
   Description :  Reset for password
**************************************/    
    public function reset_password()
    {
      return view('admin.password_reset.reset_password');
    }
/*********************************************
   Date        : 01/03/2024
   Description :  check current password
*********************************************/ 
public function CheckCurrentPassword(Request $request)
{
    $input = $request->all();
    $user = auth()->user();
    if (!Hash::check($input['old_pswd'], $user->password)) {
        return response()->json([
          'message'   => "Your Current Password Mismatch!",
          'success'   => 1,
        ]);
    }
}
/*******************************************
   Date        : 01/03/2024
   Description : Password reset submission
*******************************************/    
    public function reset_password_submit(Request $req)
    {
      $validatedData = $req->validate([
          'password' => 'required|min:6|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
          'password-confirm' => 'required_with:password|same:password|min:6'
      ], [
          'password.required' => 'Please enter the atleast one Capital letter,small letter,numbers and special letters.',
          'password-confirm.required' => 'Please enter the same password.',
      ]);
      $id = Cookie::get('user_id');
      User::where('id',$id)->update(['password'=>Hash::make($req->password)]);
      $Passwordhistroy=Passwordhistroy::where('user_id',$id)->first();
      Passwordhistroy::where('user_id',$id)->update(['password_new'=>$req->password,'password_old'=>$Passwordhistroy->password_new]);
      Cookie::queue(Cookie::forget('user_id'));
      // Session::flash('message', ['text'=>'Succssfully updated the password!....','type'=>'success']);
      return redirect('/')->with('message','Succssfully updated the password!');;
    }   
/**********************************
   Date        : 01/03/2024
   Description :  Forgot password
**********************************/     
    public function submit(Request $req)
    {
      // $this->validate($req,
      // [
      //     'email'=>['required', 'email', 'max:255','email:rfc,dns'],
      //     'captcha' => ['required','captcha']
      // ]);
      $validatedData = $req->validate([
          'email' => 'required|email|email:rfc,dns|max:255',
          'captcha'=>'required|captcha'
      ], [
          'email.required' => 'Please enter the valid email.',
          'captcha.required' => 'Please enter the proper captcha.',
      ]);
      $check_exist=User::where('email',$req->email)->exists();
      if($check_exist==true)
      {
          $otp=random_int(100000, 999999);
          User::where('email',$req->email)->update(['otp'=>$otp]);
          $user_details=User::where('email',$req->email)->first();
          $details = [
            'type' => 'Otp',
            'customer_details' => $user_details,
            'subject' => "You are received an otp !",
          ];
          $response=send_otp_to_email($req->email,$details,$blade="admin.email.send_otp",$subject="OTP Detail",$user_details->user_name);
          $responseData = json_decode($response);
          if($responseData->status=="0" && $responseData->msg=="Success")
          {
            $id=$user_details->id;
            $cookie = Cookie::queue(Cookie::make('user_id', $id, 20));
            Session::flash('message', ['text'=>'An OTP send it to the email.!','type'=>'success']);
            return redirect('forgot_password_otp');
          }
          else
          {
            Session::flash('message', ['text'=>'Not send an email.!','type'=>'danger']);
             return view('admin.password_reset.forgot_password');
          }
      }
      else
      {
          Session::flash('message', ['text'=>'Email does not exist.!','type'=>'danger']);
          return view('admin.password_reset.forgot_password');
      }
      
    }


    public function reloadCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }
}
