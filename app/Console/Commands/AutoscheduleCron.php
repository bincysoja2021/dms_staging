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
        $data=DB::table('auto_schedule_document')->where('status',"Active")->get();
        $today=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y H:i');
        for( $i = 0; $i < count($data); $i++)
        {
            if($data[$i]->end_date===NULL)
            {
                if($today === $data[$i]->start_date.' '.$data[$i]->time)
                {
                    $files = Storage::disk('d-drive')->allFiles('/');
                    foreach ($files as $key=>$file) 
                    {
                        $extension_name = pathinfo($file, PATHINFO_FILENAME);
                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                        // $parser = new \Smalot\PdfParser\Parser();
                        // $pdf = $parser->parseFile(file_get_contents($file));
                        // $text = $pdf->getText();
                        // print_r(\Log::info($text));

                        $pdfPath = Storage::disk('d-drive')->path($file);

                        // Generate a unique filename for the image
                        $outputPrefix = 'prefix_' . uniqid() . '.jpg';

                        // Execute convert command to convert PDF to image
                        $command = "magick convert -density 300 {$pdfPath}[0] {$outputPrefix}";
                        shell_exec($command);

                        // Read the converted image file
                        $imageData = file_get_contents($outputPrefix);

                        // Upload the image file to the FTP server
                        Storage::disk('ftp')->put($outputPrefix, $imageData);

                        if ($extension === 'pdf') 
                        {
                            DB::table('auto_schedule_document')->where('id',$data[$i]->id)->update(['status'=>"Inactive"]);
                            // Perform operations on PDF files
                            $fileContents = Storage::disk('d-drive')->get($file);
                            Storage::disk('ftp')->put($file, $fileContents);
                            if (preg_match('/^(PSD)-/', $file)) 
                            {
                                $Document=DB::table('documents')->insertGetId(['user_id'=>'1','user_name'=>'Admin','date'=>Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'),'status'=>"Success",'filename'=>basename($file),'automatic'=>'1','invoice_number'=>$extension_name,'document_type'=>"Invoice",'thumbnail'=>basename($file),'doc_id'=>$extension_name]);
                            }
                            else if (preg_match('/^(SB)-/', $file)) 
                            {
                                $Document=DB::table('documents')->insertGetId(['user_id'=>'1','user_name'=>'Admin','date'=>Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'),'status'=>"Success",'filename'=>basename($file),'automatic'=>'1','shipping_bill_number'=>$extension_name,'document_type'=>"Shipping Bill",'thumbnail'=>$outputPrefix,'doc_id'=>$extension_name]);
                            }
                            else
                            {
                               $Document= DB::table('documents')->insertGetId(['user_id'=>'1','user_name'=>'Admin','date'=>Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'),'status'=>"Success",'filename'=>basename($file),'automatic'=>'1','sales_order_number'=>$extension_name,'document_type'=>"Sales Order",'thumbnail'=>$outputPrefix,'doc_id'=>$extension_name]);
                            }
                            \Log::info("success");
                        } 
                        else 
                        {
                            $fileContents = Storage::disk('d-drive')->get($file);
                            Storage::disk('ftp')->put($file, $fileContents);
                            // Handle other file types if needed
                            $Document=DB::table('documents')->insertGetId(['user_id'=>'1','user_name'=>'Admin','date'=>Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'),'status'=>"Failed",'filename'=>basename($file),'automatic'=>'1','invoice_number'=>$extension_name,'document_type'=>"Invoice",'thumbnail'=>basename($file),'doc_id'=>$file]);
                            \Log::info("success");
                            \Log::warning("Ignoring file with extension: $extension");
                        }
                        Storage::disk('d-drive')->delete($file);
                        $check_exist=Invoicedate::where('invoice_id',$extension_name)->exists();
                        if($check_exist==true)
                        {
                            $submit_invoice_date=Invoicedate::where('invoice_id',$extension_name)->first();
                            $doc_data=Document::where('id',$Document)->update(['invoice_date'=>$submit_invoice_date->invoice_date]);
                        }


                    }

                }
                else
                {
                 \Log::info("else Cron is working fine!");
                }
            }  
        }
        
        
    }
}

