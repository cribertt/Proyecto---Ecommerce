<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'code_product',
        'product_name',
        'product_category',
        'product_variant',
    ];
    
}
