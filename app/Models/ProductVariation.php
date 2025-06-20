<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 'carat', 'price', 'sku', 'stock', 
        'weight', 'shape_id', 'category_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function shape()
    {
        return $this->belongsTo(DiamondShape::class, 'shape_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'variation_id');
    }
}
 