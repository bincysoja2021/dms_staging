<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Storage;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\File;

class ScheduleCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:cron';

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
        $data=DB::table('documents')->whereNotNull('reschedule_docs')->where('status',"Failed")->where('deleted_at',NULL)->get();
        $today=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y H:i');
        for( $i = 0; $i < count($data); $i++)
        {
          if($data[$i]->end_date===NULL)
          {
            if($today === $data[$i]->start_date.' '.$data[$i]->time)
            {
              $path=$data[$i]->reschedule_docs;
              $thumbnail_path=$data[$i]->reschedule_thumbnail_docs;
              $sourcePath = public_path('failed_document_reupload/'.$path);
              $destinationPath = public_path('failed_thumbnail_document_reupload/'.$thumbnail_path);
              DB::table('documents')->where('id',$data[$i]->id)->update(['filename'=>$data[$i]->reschedule_docs,'status'=>"Success",'thumbnail'=>$data[$i]->reschedule_thumbnail_docs]);
              Storage::disk('ftp')->put($path,file_get_contents($sourcePath));
              Storage::disk('ftp')->put($thumbnail_path,file_get_contents($destinationPath));
              \Log::info("success");
            }
            else
            {
              \Log::info("else Cron is working fine!");
            } 
          }
          else
          {
            if($today <= $data[$i]->start_date.' '.$data[$i]->time   || $today <= $data[$i]->end_date.' '.$data[$i]->time)
            {
              $path=$data[$i]->reschedule_docs;
              $thumbnail_path=$data[$i]->reschedule_thumbnail_docs;
              $sourcePath = public_path('failed_document_reupload/'.$path);
              $destinationPath = public_path('failed_thumbnail_document_reupload/'.$thumbnail_path);
              DB::table('documents')->where('id',$data[$i]->id)->update(['filename'=>$data[$i]->reschedule_docs,'status'=>"Success",'thumbnail'=>$data[$i]->reschedule_thumbnail_docs]);
              Storage::disk('ftp')->put($path,file_get_contents($sourcePath));
              Storage::disk('ftp')->put($thumbnail_path,file_get_contents($destinationPath));
              \Log::info("schdeule success");
            }
            else
            {
               \Log::info("schdeule else Cron is working fine!");
            }

          }
          
        }
    }
}
