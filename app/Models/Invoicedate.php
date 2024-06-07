<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Invoicedate extends Model
{
    use HasFactory;
    protected $table="excelimportdata";
    protected $fillable = [
    	'id',
        'invoice_date',
        'customer_name',
        'salesorder_number',
        'salesorder_date',
        'shippingbill_number',
        'shippingbill_date'
    ];
}
