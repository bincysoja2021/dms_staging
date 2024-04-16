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
      $data=DB::table('documents')->where('deleted_at',NULL)->get();
      $today=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y H:i');
      foreach($data as $key=>$value)
      {
          if($today === $value->start_date.' '.$value->time)
          {
            // $file_upload_returns=ftp_upload_docs($value->reschedule_docs,$value->reschedule_docs);
            //thumbnail
            // $ftp_thumbnail_upload_docs=ftp_thumbnail_upload_docs($value->reschedule_thumbnail_docs,$value->reschedule_thumbnail_docs);
            \Log::info("if Cron is working fine!");
          }
          else
          {
            \Log::info("else Cron is working fine!");
          }
      }
    }
}
