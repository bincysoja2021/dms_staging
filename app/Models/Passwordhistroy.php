<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passwordhistroy extends Model
{
    use HasFactory;
    protected $table="passwordhistory";
    protected $fillable = [
        'added_by',
        'user_id',
        'password_old',
        'password_new',
        'password_newly_old',
        'password_old_date',
        'password_new_date',
        'password_newly_old_date',
        'user_type'
    ];
}
