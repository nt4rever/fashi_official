<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'name', 'email', 'question',
    ];
    protected $primaryKey = 'id';
    protected $table = 'tbl_question';
}
