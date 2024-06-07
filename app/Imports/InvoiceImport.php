<?php
namespace App\Imports;

use App\Models\InvoiceDate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class InvoiceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $formattedDate = $this->transformDate($row['invoicedate']);
        $shippingbillDate = $this->transformDate($row['shippingbilldt']);
        $salesorderDate = $this->transformDate($row['contractdate']);

        

        $invoice = InvoiceDate::where('id', $row['invoiceno'])->first();

        if ($invoice) {
            // Update existing invoice
            $invoice->update([
                'invoice_date' => $formattedDate,
                'customer_name' => $row['customer'],
                'salesorder_number' => $row['contractno'] ?? null,
                'salesorder_date' => $salesorderDate,
                'shippingbill_number' => $row['shipbillno'] ?? null,
                'shippingbill_date' => $shippingbillDate,
            ]);
        } else {
            // Insert new invoice
            return new InvoiceDate([
                'id' => $row['invoiceno'],
                'invoice_date' => $formattedDate,
                'customer_name' => $row['customer'],
                'salesorder_number' => $row['contractno'] ?? null,
                'salesorder_date' => $salesorderDate,
                'shippingbill_number' => $row['shipbillno'] ?? null,
                'shippingbill_date' => $shippingbillDate,
            ]);
        }

    }

    private function transformDate($value)
    {
        if (is_numeric($value)) {
            return Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d'));
        }
        return null;
    }
}
