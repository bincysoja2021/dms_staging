<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Auto_scheduleDocument extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="auto_schedule_document";
    protected $fillable = [
        'today_date',
        'start_date',
        'end_date',
        'time'
    ];
}
