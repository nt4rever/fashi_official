<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'home_slider_image', 'home_slider_desc', 'home_slider_sale'
    ];
    protected $primaryKey = 'home_slider_id';
    protected $table = 'tbl_home_slider';
}
