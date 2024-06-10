<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Storage;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\File;
use Auth;
use App\Models\Invoicedate;
use App\Models\Document;


class AutoscheduleCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Autoschedule:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
{
    $data = DB::table('auto_schedule_document')->where('status', "Active")->get();
    $today = Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y H:i'); // Format after subtraction

    for ($i = 0; $i < count($data); $i++) 
    {
        if ($data[$i]->end_date === NULL) 
        {
            if ($today === $data[$i]->start_date . ' ' . $data[$i]->time) 
            {
                $files = Storage::disk('d-drive')->allFiles('/');
        
                $filteredFiles = array_filter($files, function($file) {
                        return basename($file) !== 'desktop.ini';
                });

                foreach ($filteredFiles as $key => $file) 
                {
                    $extension_name = pathinfo($file, PATHINFO_FILENAME);
                    $invoiceNumber = str_replace('PSD-', '', $extension_name);
                    $sbinvoiceNumber = str_replace('SB-', '', $extension_name);

                    $extension = pathinfo($file, PATHINFO_EXTENSION);

                    $pdfPath = Storage::disk('d-drive')->path($file);
                    $fileSize = Storage::disk('d-drive')->size($file);
            

                    // Generate a unique filename for the image
                    $outputPrefix = 'prefix_' . uniqid() . '.jpg';

                    // Execute convert command to convert PDF to image
                    $command = "magick convert -density 300 {$pdfPath}[0] {$outputPrefix}";
                    shell_exec($command);

                    // Check if the output file exists before trying to read it
                    if (file_exists($outputPrefix)) 
                    {
                        // Read the converted image file
                        $imageData = file_get_contents($outputPrefix);

                        // Upload the image file to the FTP server
                        Storage::disk('ftp')->put($outputPrefix, $imageData);

                        if ($extension === 'pdf' && $fileSize >= 15360 && $fileSize <= 2097152) 
                        {
                            DB::table('auto_schedule_document')->where('id', $data[$i]->id)->update(['status' => "Inactive"]);
                            // Perform operations on PDF files
                            $fileContents = Storage::disk('d-drive')->get($file);
                            $check_exist=Invoicedate::where('id',$invoiceNumber)->exists();
                            $sb_check_exist=Invoicedate::where('shippingbill_number',$sbinvoiceNumber)->exists();
                            Storage::disk('ftp')->put($file, $fileContents);

                            if (preg_match('/^(PSD)-/', $file)) 
                            {
                                if($check_exist==true)
                                {
                                 $Document=DB::table('documents')->insertGetId(['user_id' => '1', 'user_name' => 'Admin', 'date' => Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'), 'status' => "Success", 'filename' => basename($file), 'automatic' => '1', 'invoice_number' => $invoiceNumber, 'document_type' => "Invoice", 'thumbnail' => $outputPrefix, 'doc_id' => $extension_name]);
                                }
                                else
                                {
                                    $Document=DB::table('documents')->insertGetId(['user_id'=>'1','user_name'=>'Admin','date'=>Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'),'status'=>"Failed",'filename'=>basename($file),'automatic'=>'1','invoice_number'=>$invoiceNumber,'document_type'=>"Invoice",'thumbnail'=>basename($file),'doc_id'=>$extension_name]);
                                     notification_data($id="1",$type="Admin",$date=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y'),$message="Invoice number does not exists.",$message_title="Invoice number does not exists.",$status="Failed",$doc_id=$Document);
                                }
                            } else if (preg_match('/^(SB)-/', $file)) 
                            {
                                if($sb_check_exist==true)
                                {
                                 $Document=DB::table('documents')->insertGetId(['user_id' => '1', 'user_name' => 'Admin', 'date' => Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'), 'status' => "Success", 'filename' => basename($file), 'automatic' => '1', 'shipping_bill_number' => $sbinvoiceNumber, 'document_type' => "Shipping Bill", 'thumbnail' => $outputPrefix, 'doc_id' => $extension_name]);
                                }
                                else
                                {
                                    $Document=DB::table('documents')->insertGetId(['user_id'=>'1','user_name'=>'Admin','date'=>Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'),'status'=>"Failed",'filename'=>basename($file),'automatic'=>'1','shipping_bill_number'=>$sbinvoiceNumber,'document_type'=>"Shipping Bill",'thumbnail'=>$outputPrefix,'doc_id'=>$extension_name]); 
                                    notification_data($id="1",$type="Admin",$date=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y'),$message="Invoice number does not exists.",$message_title="Invoice number does not exists.",$status="Failed",$doc_id=$Document);  
                                }
                            } else 
                            {
                                 
                                 $Document=DB::table('documents')->insertGetId(['user_id' => '1', 'user_name' => 'Admin', 'date' => Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'), 'status' => "Failed", 'filename' => basename($file), 'automatic' => '1', 'sales_order_number' => $extension_name, 'document_type' => "Sales Order", 'thumbnail' => $outputPrefix, 'doc_id' => $extension_name]);
                                 notification_data($id="1",$type="Admin",$date=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y'),$message="Failed to upload auto schdeuled files  provide supported filename.",$message_title="Failed to upload auto schdeuled files provide supported filename.",$status="Failed",$doc_id=$Document);
                                
                            }
                            \Log::info("success");
                        } else 
                        {
                            $fileContents = Storage::disk('d-drive')->get($file);
                            Storage::disk('ftp')->put($file, $fileContents);
                            // Handle other file types if needed
                             $Document=DB::table('documents')->insertGetId(['user_id' => '1', 'user_name' => 'Admin', 'date' => Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'), 'status' => "Failed", 'filename' => basename($file), 'automatic' => '1', 'invoice_number' => $extension_name, 'document_type' => "Invoice", 'thumbnail' => basename($file), 'doc_id' => basename($file)]);
                            \Log::warning("Ignoring file with extension: $extension");
                            notification_data($id="1",$type="Admin",$date=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y'),$message="Failed to upload auto schdeuled files  provide supported exiension.",$message_title="Failed to upload auto schdeuled files provide supported exiension.",$status="Failed",$doc_id=$Document);
                        }
                        Storage::disk('d-drive')->delete($file);
                        $check_exist=Invoicedate::where('id',$invoiceNumber)->exists();
                        if($check_exist==true)
                        {
                            $submit_invoice_date=Invoicedate::where('id',$invoiceNumber)->first();
                            $doc_data=Document::where('id',$Document)->update(['invoice_date'=>$submit_invoice_date->invoice_date,'shipping_bill_number'=>$submit_invoice_date->shippingbill_number,'shippingbill_date'=>$submit_invoice_date->shippingbill_date]);
                        }
                        $sb_check_exist=Invoicedate::where('shippingbill_number',$sbinvoiceNumber)->exists();

                        if($sb_check_exist==true)
                        {
                            $submit_invoice_date=Invoicedate::where('shippingbill_number',$sbinvoiceNumber)->first();
                            $doc_data=Document::where('id',$Document)->update(['invoice_number'=>$submit_invoice_date->id,'invoice_date'=>$submit_invoice_date->invoice_date,'shipping_bill_number'=>$submit_invoice_date->shippingbill_number,'shippingbill_date'=>$submit_invoice_date->shippingbill_date]);
                        }
                    } else {
                        \Log::error("Failed to convert PDF to image: {$pdfPath}");
                        $Document=DB::table('documents')->insertGetId(['user_id'=>'1','user_name'=>'Admin','date'=>Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'),'status'=>"Failed",'filename'=>basename($file),'automatic'=>'1','shipping_bill_number'=>basename($file),'document_type'=>"Shipping Bill",'thumbnail'=>basename($file),'doc_id'=>basename($file)]); 
                        notification_data($id="1",$type="Admin",$date=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y'),$message="Failed to convert PDF to image.",$message_title="Failed to convert PDF to image.",$status="Failed",$doc_id=$Document);  
                    }
                }
                 notification_data($id="1",$type="Admin",$date=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y'),$message="Auto schdeuled  upload files Successfully.",$message_title="Automatic schdeuled Document upload",$status="Completed",$doc_id="");
            } else {
                \Log::info("else Cron is working fine!");
            }
        }  
    }
}




    
}
