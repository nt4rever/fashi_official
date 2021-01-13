<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'customer_id', 'shipping_id', 'payment_id', 'order_total', 'order_status', 'coupon_code', 'discount', 'message'
    ];
    protected $table = 'tbl_order';
    protected $primaryKey = 'order_id';

    public function shipping()
    {
        return $this->belongsTo(Shipping::class, 'shipping_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function order_detail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}
