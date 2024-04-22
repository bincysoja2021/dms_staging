<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Storage;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\File;
use Auth;


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
        $data=DB::table('auto_schedule_document')->whereNull('end_date')->first();
        $today=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y H:i');
        
        if($data->end_date===NULL)
        {
            if($today === $data->start_date.' '.$data->time)
            {
                $files = Storage::disk('d-drive')->allFiles('/');

                foreach ($files as $key=>$file) 
                {
                    $extension = pathinfo($file, PATHINFO_EXTENSION);

                    if ($extension === 'pdf') 
                    {
                        // Perform operations on PDF files
                        $fileContents = Storage::disk('d-drive')->get($file);
                        Storage::disk('ftp')->put($file, $fileContents);
                        // Storage::disk('d-drive')->delete($file);
                        // Process file contents or perform any other operation
                        \Log::info("success");
                        // \Log::info("Processing PDF file: $file");
                        // \Log::info($fileContents);
                        DB::table('documents')->insert(['user_id'=>'1','user_name'=>'Admin','date'=>Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'),'status'=>"Success"]);
                    } 
                    else 
                    {
                        $fileContents = Storage::disk('d-drive')->get($file);
                        Storage::disk('ftp')->put($file, $fileContents);
                        \Log::info("success");
                        // Handle other file types if needed
                        \Log::warning("Ignoring file with extension: $extension");
                        DB::table('documents')->insert(['user_id'=>'1','user_name'=>'Admin','date'=>Carbon::now()->timezone('Asia/Kolkata')->format('Y-m-d'),'status'=>"Failed"]);
                    }
                Storage::disk('d-drive')->delete($file);
                }

            }
            else
            {
                \Log::info("else Cron is working fine!");
            }
        }
        else
        {
            if($today <= $data->start_date.' '.$data->time   || $today <= $data->end_date.' '.$data->time)
            {

            }
            else
            {
                \Log::info("schdeule else Cron is working fine!");
            }
        }
        
       
        // \Log::error($files);

    }
}
