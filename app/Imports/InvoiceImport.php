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
    
    public function model(array $row)
   {
        // \Log::info('Importing row: ', $row);
        if (!isset($row['invoiceno']) || !isset($row['invoicedate']) || !isset($row['customer'])) {
            \Log::error('Row is missing required fields: ', $row);
            return null; 
        }
        $formattedDate = null;
        try 
        {
            $date = \Carbon\Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['invoicedate'])->format('Y-m-d'));
            $formattedDate = $date->format('Y-m-d');
        } 
        catch (\Exception $e) 
        {
            \Log::error('Error formatting date for row: ' . $e->getMessage(), $row);
            $formattedDate = null;
        }

        $check_exist = InvoiceDate::where('invoice_id', $row['invoiceno'])->exists();
        if ($check_exist) 
        {
            \Log::info('Invoice already exists: ' . $row['invoiceno']);
            return null;
        }
        else 
        {
            return new InvoiceDate([
                'invoice_id' => $row['invoiceno'],
                'invoice_date' => $formattedDate,
                'customer_name' => $row['customer'],
            ]);
        }
    }

}
