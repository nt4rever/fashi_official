<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'customer_id', 'comment_content', 'comment_time', 'product_id', 'reply_id'
    ];
    protected $table = 'tbl_comment';
    protected $primaryKey = 'comment_id';
    public function childComment()
    {
        return $this->hasMany(Comment::class, 'reply_id')->take(10);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function rating()
    {
        return $this->hasOne(Rating::class, 'customer_id', 'customer_id')->where('product_id', $this->product_id);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
