<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Invoicedate extends Model
{
    use HasFactory;
    protected $table="invoice_date";
    protected $fillable = [
        'invoice_date',
        'filename'
    ];
}
