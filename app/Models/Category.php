<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'parent_id',
        'category_name',
        'category_alias',
        'category_description',
        'is_display_front',
        'category_image',
        'category_header_banner',
        'category_status',
        'seo_url',
        'category_meta_title',
        'category_meta_description',
        'category_meta_keyword',
        'category_h1_tag',
        'sort_order',
        'deleted',
        'category_date_added',
        'category_date_modified',
        'added_by',
        'updated_by',
    ];

    protected $casts = [
        'is_display_front' => 'boolean',
        'deleted' => 'boolean',
    ];

    /**
     * Self-referencing relationship: Parent category
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Self-referencing relationship: Child categories
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Optional: User who added this category
     */
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Optional: User who last updated this category
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for active & visible categories
     */
    public function scopeActive($query)
    {
        return $query->where('category_status', 1)->where('deleted', 0);
    }
}
