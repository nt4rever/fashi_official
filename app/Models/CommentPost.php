<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentPost extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'customer_id', 'time', 'content', 'post_id'
    ];
    protected $primaryKey = 'id';
    protected $table = 'tbl_post_comment';
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
