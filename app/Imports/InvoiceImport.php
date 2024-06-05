<?php

namespace App\Imports;

use App\Models\InvoiceDate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use DateTime;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoiceImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     $formattedDate = null;
    //     try 
    //     {
    //         $date = \Carbon\Carbon::createFromFormat('j-M-Y', $row[1]);
    //         $formattedDate = $date->format('Y-m-d');

    //     } 
    //     catch (\Exception $e) 
    //     {
    //         $formattedDate = null; 
    //     }
    //     $check_exist=InvoiceDate::where('invoice_id',$row[0])->exists();
    //     if($check_exist===true)
    //     {

    //     }
    //     else
    //     {
    //         return new InvoiceDate([
    //             'invoice_id' => $row[0],
    //             'invoice_date' => $formattedDate,
    //             'customer_name' => $row[6],
    //         ]); 
    //     }

        
    // }

    // public function model(array $row)
    // {
    //     // Log the row for debugging
    //     \Log::info('Importing row: ', $row);

    //     // Ensure the row has the expected number of elements
    //     if (count($row) < 7) 
    //     {
    //         \Log::error('Row does not have enough columns: ', $row);
    //         return null; // Skip this row or handle the error as needed
    //     }

    //     $formattedDate = null;
    //     try 
    //     {
    //         $date = \Carbon\Carbon::createFromFormat('j-M-Y', $row[1]);
    //         $formattedDate = $date->format('Y-m-d');
    //     } 
    //     catch (\Exception $e) 
    //     {
    //         \Log::error('Error formatting date for row: ' . $e->getMessage(), $row);
    //         $formattedDate = null;
    //     }

    //     $check_exist = InvoiceDate::where('invoice_id', $row[0])->exists();
    //     if ($check_exist === true) 
    //     {
    //         \Log::info('Invoice already exists: ' . $row[0]);
    //         return null;
    //     } 
    //     else 
    //     {
    //         return new InvoiceDate([
    //         'invoice_id' => $row[0],
    //         'invoice_date' => $formattedDate,
    //         'customer_name' => $row[6],
    //         ]);
    //     }
    // }

    public function model(array $row)
   {
        // Log the row for debugging
        \Log::info('Importing row: ', $row);

        // Ensure the required keys are present
        if (!isset($row['invoice_id']) || !isset($row['invoice_date']) || !isset($row['customer'])) {
            \Log::error('Row is missing required fields: ', $row);
            return null; // Skip this row or handle the error as needed
        }

        $formattedDate = null;
        try 
        {
            // Assuming 'invoice_date' is a date in Excel serial number format
            $date = \Carbon\Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['invoice_date'])->format('Y-m-d'));
            $formattedDate = $date->format('Y-m-d');
        } 
        catch (\Exception $e) 
        {
            \Log::error('Error formatting date for row: ' . $e->getMessage(), $row);
            $formattedDate = null;
        }

        $check_exist = InvoiceDate::where('invoice_id', $row['invoice_id'])->exists();
        if ($check_exist) 
        {
            \Log::info('Invoice already exists: ' . $row['invoice_id']);
            return null;
        }
        else 
        {
            return new InvoiceDate([
                'invoice_id' => $row['invoice_id'],
                'invoice_date' => $formattedDate,
                'customer_name' => $row['customer'],
            ]);
        }
    }

}
