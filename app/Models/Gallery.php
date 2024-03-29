<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'id', 'name', 'path', 'product_id'
    ];
    protected $primaryKey = 'id';
    protected $table = 'tbl_product_gallery';
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
