<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Storage;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\File;


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
        $fileContents = Storage::disk('d-drive')->get('2010catalog_6614cb8978d44.pdf');
        if (Storage::disk('d-drive')->exists('2010catalog_6614cb8978d44.pdf')) {
        \Log::info('File exists on D drive');
        } else {
        \Log::error('File does not exist on D drive');
        }

    }
}
