<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'category_name', 'category_desc', 'category_status', 'category_parentId', 'category_slug', 'role', 'category_order'
    ];
    protected $primaryKey = 'category_id';
    protected $table = 'tbl_category';
    public function categoryChildrent()
    {
        return $this->hasMany(Category::class, 'category_parentId')->orderBy('category_order', 'asc');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id')->latest()->take(2);
    }

    public function categoryParent()
    {
        return $this->belongsTo(Category::class, 'category_parentId', 'category_id');
    }
    public function count_product()
    {
        if ($this->category_parentId != 0) {
            return $this->hasMany(Product::class, 'category_id', 'category_id')->where('product_status', 0)->count();
        } else {
            return $this->categoryChildrent()->count();
        }
    }
}
