<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'product_price', 'product_sales_quantity', 'order_attribute'
    ];
    protected $table = 'tbl_order_detail';
    protected $primaryKey = 'order_detail_id';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
