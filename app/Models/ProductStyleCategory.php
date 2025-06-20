<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStyleCategory extends Model
{
    protected $table = 'products_style_category';
    protected $primaryKey = 'psc_id';
    public $timestamps = false;

    protected $fillable = [
        'psc_category_id',
        'psc_name',
        'psc_image',
        'psc_status',
        'psc_sort_order',
        'psc_alias',
        'psc_display_in_front',
        'date_added',
        'date_modified',
        'added_by',
        'updated_by',
    ];
}
