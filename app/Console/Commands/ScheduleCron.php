<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Storage;
use Carbon\Carbon;
use Session;

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
          if($today === $data[$i]->start_date.' '.$data[$i]->time)
          {
            $path=$data[$i]->reschedule_docs;
            $path_details = str_replace('failed_document_reupload/', '', $path);

            $thumbnail_path=$data[$i]->reschedule_thumbnail_docs;
            $thumbnail_path_details = str_replace('failed_thumbnail_document_reupload/', '', $thumbnail_path);

            DB::table('documents')->where('id',$data[$i]->id)->update(['filename'=>$path_details,'status'=>"Success",'thumbnail'=>$thumbnail_path_details]);
            Storage::disk('ftp')->put($path_details,$path_details);
            Storage::disk('ftp')->put($thumbnail_path_details,$thumbnail_path_details);
            \Log::info($path_details);
          }
          else
          {
            \Log::info("else Cron is working fine!");
          }
      }
    }
}
