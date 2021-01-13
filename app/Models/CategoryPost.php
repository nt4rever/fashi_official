<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'category_post_name', 'category_post_desc', 'category_post_status', 'category_post_slug',
    ];
    protected $primaryKey = 'category_post_id';
    protected $table = 'tbl_category_post';
}
