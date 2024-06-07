<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InvoiceImport;
use Carbon\Carbon;

class ExcelUploadInvoiceDate extends Command
{
    protected $signature = 'Exceluploadinvoicedateschedule:cron';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today=Carbon::now()->timezone('Asia/Kolkata')->format('d-m-Y H:i');
        $files = Storage::disk('e-drive')->allFiles('/');
        // if($today === $today)
        // {
            foreach ($files as $file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                if ($extension === 'xlsx') {
                    set_time_limit(0);
                    ini_set('memory_limit', '512M');

                    try {
                        $filePath = Storage::disk('e-drive')->path($file);
                        Excel::import(new InvoiceImport, $filePath);
                        \Log::info("Import successful for file: " . $file);
                        // Storage::disk('e-drive')->delete($file);
                    } catch (\Exception $e) {
                        \Log::error("Import failed for file: " . $file . " with error: " . $e->getMessage());
                    }
                } else {
                    \Log::info("Skipped non-xlsx file: " . $file);
                }
            }
        // }    
    }
}
