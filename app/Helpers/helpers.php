<?php
/*
  Date         : 01/03/2024
  Description  : Common sent otp share with registerd email
*/

use Illuminate\Support\Facades\Mail;
use App\Models\Notification;

if(!function_exists('send_otp_to_email'))
{
    function send_otp_to_email($email_id,$details,$blade,$subject,$name)
    {
        try
        {
           
            $curl = curl_init();
            // $cFile = new \CURLFile($file_path);
           
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://reviewtreasures.com/mail_send/index.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
            'mailer' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port'=>587,
            'user_name'=>'online@naseemalrabeeh.com', //Mail USer Name
            'password'=>'ghbpigjiunnjrgzw',  //MAIl APP Key
            'mail_encription'=>'tls',  //ENcription SSL,TLS
            'from_address'=>'online@naseemalrabeeh.com',
            'from_name'=>'Document management system',
            'to_name'=>$name,
            'to_address'=>$email_id,
            'subject'=>$subject,
            'html_content'=>View($blade, compact('details'))->render(),
       
            ),
            ));
             
            $response = curl_exec($curl);
            $err      = curl_error($curl);

            curl_close($curl);
            if ($err) {
              return response()->json(['message'=>$err,'success' => 'error','data'=>[],'statusCode'=>401], 401);
            } else {

            return $response;
            }
        }
        catch (\Exception $e)
        {
           $msg=$e->getMessage();
           return response()->json(['message'=>$msg,'success' => 'error','data'=>[],'statusCode'=>401], 401);
        }

    }
}



if(!function_exists('notification_data'))
{
    function notification_data($id,$user_type,$date,$message,$message_title,$status_val,$doc_id)
    {
        try
        {
            Notification::Create([
                'user_id'=>$id,
                'user_type'=>$user_type,
                'date'=>$date,
                'message_title'=>$message_title,
                'message'=>$message,
                'status'=>$status_val,
                'doc_id'=>$doc_id
            ]);
           
        }
        catch (\Exception $e)
        {
           $msg=$e->getMessage();
           return response()->json(['message'=>$msg,'success' => 'error','data'=>[],'statusCode'=>401], 401);
        }

    }
}