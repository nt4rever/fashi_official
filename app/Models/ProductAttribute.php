<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 'size', 'color','extra_price'
    ];
    protected $primaryKey = 'id';
    protected $table = 'tbl_product_attribute';
}
