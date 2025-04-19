<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiamondShape extends Model
{
    use HasFactory;
    protected $table = 'diamond_shape_master';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'ALIAS',
        'shortname',
        'rap_shape',
        'image',
        'image2',
        'image3',
        'image4',
        'svg_image',
        'remark',
        'display_in_front',
        'display_in_stud',
        'sort_order',
        'date_added',
        'date_modify',
    ];
    
}
