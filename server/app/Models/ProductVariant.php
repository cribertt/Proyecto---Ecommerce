<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'product_id',
        'color',
        'variant',
        'updated_at'
    ];
}
