<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'brand_name', 'brand_desc', 'brand_status',
    ];
    protected $primaryKey = 'brand_id';
    protected $table = 'tbl_brand';
    public function product()
    {
        return $this->hasMany(Product::class, 'brand_id', 'brand_id');
    }
}
