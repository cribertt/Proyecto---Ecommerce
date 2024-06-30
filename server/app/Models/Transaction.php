<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'id_owner',
        'commerceOrder',
        'token',
        'method_pay',
        'amount',
        'created_at',
        'updated_at'
    ];
}
