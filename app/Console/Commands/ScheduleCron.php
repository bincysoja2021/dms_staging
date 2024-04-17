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
      $data=DB::table('documents')->whereNotNull('reschedule_docs')->where('deleted_at',NULL)->get();
      $today=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y H:i');
      for( $i = 0; $i < count($data); $i++)
      {
          if($today === $data[$i]->start_date.' '.$data[$i]->time)
          {
            // $file_upload_returns=ftp_upload_docs($value->reschedule_docs,$value->reschedule_docs);
            //thumbnail
            // $ftp_thumbnail_upload_docs=ftp_thumbnail_upload_docs($value->reschedule_thumbnail_docs,$value->reschedule_thumbnail_docs);
            // $file_local = Storage::disk('local')->get($data[$i]->reschedule_thumbnail_docs);
            // $file_ftp = Storage::disk('ftp')->put($file_local, $file_local);
            \Log::info("if Cron is working fine!");
          }
          else
          {
            \Log::info("else Cron is working fine!");
          }
      }
    }
}
