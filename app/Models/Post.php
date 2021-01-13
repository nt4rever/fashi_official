<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'post_title', 'post_desc', 'post_content', 'post_meta_desc', 'post_meta_keyword', 'post_image', 'post_status',
        'category_post_id', 'post_slug', 'post_views'
    ];
    protected $primaryKey = 'post_id';
    protected $table = 'tbl_post';

    public function category()
    {
        return $this->belongsTo(CategoryPost::class, 'category_post_id');
    }

    public function next()
    {
        return $this->hasOne(Post::class, 'category_post_id', 'category_post_id')
            ->where('post_id', '>', $this->post_id)->where('post_status', 0)->take(1);
    }

    public function prev()
    {
        return $this->hasOne(Post::class, 'category_post_id', 'category_post_id')
            ->where('post_id', '<', $this->post_id)->where('post_status', 0)->latest()->take(1);
    }
    public function comment()
    {
        return $this->hasMany(CommentPost::class, 'post_id', 'post_id');
    }
}
