<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealProduct extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'product_id', 'deal_desc', 'deal_time',
    ];
    protected $primaryKey = 'deal_id';
    protected $table = 'tbl_deal_product';
    public function product()
    {
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }
}
