<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPage extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'map_frame', 'phone', 'address','email'
    ];
    protected $primaryKey = 'id';
    protected $table = 'tbl_page_contact';
}
