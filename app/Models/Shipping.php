<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'shipping_name', 'shipping_address', 'shipping_phone', 'shipping_email', 'shipping_note'
    ];
    protected $table = 'tbl_shipping';
    protected $primaryKey = 'shipping_id';
    public function order()
    {
        return $this->hasOne(Order::class, 'shipping_id');
    }
}
