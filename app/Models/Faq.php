<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'faq_question', 'faq_answer'
    ];
    protected $table = 'tbl_faq';
    protected $primaryKey = 'faq_id';
}
