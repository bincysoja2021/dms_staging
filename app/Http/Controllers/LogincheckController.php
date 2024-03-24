<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Redirect;
use Session;
use Hash;
use Auth;

class LogincheckController extends Controller
{
/*****************************************
   Date        : 01/03/2024
   Description :  Login for Submission
******************************************/
    public function login(Request $req)
    {
        $validatedData = $req->validate([
          'email' => 'required|email|email:rfc,dns|max:255',
          'password'=>'required',
      ], [
          'email.required' => 'Please enter the email.',
          'password.required' => 'Please enter the password.',
      ]);
        $credentials = $req->only('email', 'password');
        if (Auth::attempt($credentials)==true)
        {
          $user=User::where('id', Auth::user()->id)->where('active_status',1)->exists();
          if( $user==true)
          {
            return redirect()->route('home');
          }
          else
          {
            return redirect()->route('form_login')->with('message','Your account has been deactivated.please contact the administrater!');
          }
        }
        else
        {
           return redirect()->route('form_login')->with('message','Your mailid or password does not match!');
        }

    }
    
}
