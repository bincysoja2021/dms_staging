<?php

namespace App\Imports;

use App\Models\InvoiceDate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use DateTime;
use Carbon\Carbon;

class InvoiceImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // $formattedDate = null;
        // try 
        // {
        //     $date = Carbon::createFromFormat('j-M-Y', $row[1]);
        //     $formattedDate = $date->format('Y-m-d');
        // } 
        // catch (\Exception $e) 
        // {
        //     $formattedDate = null; 
        // }
        $check_exist=InvoiceDate::where('invoice_id',$row[0])->exists();
        if($check_exist===true)
        {

        }
        else
        {
            return new InvoiceDate([
                'invoice_id' => $row[0],
                'invoice_date' => $row[1],
                'customer_name' => $row[6],
            ]); 
        }

        
    }
}
