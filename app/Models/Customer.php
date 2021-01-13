<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'customer_email', 'customer_name', 'customer_password', 'customer_token', 'customer_address', 'customer_phone', 'customer_image', 'customer_status'
    ];
    protected $table = 'tbl_customer';
    protected $primaryKey = 'customer_id';
}
