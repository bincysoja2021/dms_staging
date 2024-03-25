<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Document extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="documents";
    protected $fillable = [
        'doc_id',
        'user_id',
        'date',
        'document_type',
        'invoice_number',
        'invoice_date',
        'sales_order_number',
        'shipping_bill_number',
        'company_name',
        'company_id',
        'filename',
        'status',
        'user_name',
        'thumbnail'
    ];
}
