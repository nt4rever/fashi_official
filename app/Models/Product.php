<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FullTextSearch;

class Product extends Model
{
    use HasFactory;
    use FullTextSearch;
    // use SoftDeletes;
    const paginates = 5;
    public $timestamps = true;
    protected $fillable = [
        'product_name', 'product_image', 'product_desc', 'product_content', 'product_price', 'product_price_discount', 'product_quantity',
        'category_id', 'brand_id', 'product_order', 'product_status', 'product_tag', 'product_slug', 'product_sales_quantity', 'product_views'
    ];

    protected $searchable = [
        'product_name'
    ];

    protected $table = 'tbl_product';
    protected $primaryKey = 'product_id';
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function gallery()
    {
        return $this->hasMany(Gallery::class, 'product_id');
    }

    public function attribute()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }

    public function rating()
    {
        return $this->hasMany(Rating::class, 'product_id');
        // $rating = $pro->avg('rating');
        // return round($rating);
    }
}
