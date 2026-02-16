<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    //
    use HasFactory, SoftDeletes;

        protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'is_active',
        'sort_order'
    ];

        protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all active products for this category.
     */
    public function activeProducts()
    {
        return $this->products()->where('status', 'active');
    }

    /**
     * Get the reviews through products.
     */
    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Product::class);
    }

    /**
     * Check if category has any active products.
     */
    public function hasActiveProducts()
    {
        return $this->activeProducts()->exists();
    }

    /**
     * Get the count of active products.
     */
    public function getActiveProductsCountAttribute()
    {
        return $this->activeProducts()->count();
    }

    /**
     * Get the full path of the category (for breadcrumbs).
     */
    public function getPathAttribute()
    {
        $path = collect();
        
        $category = $this;
        while ($category) {
            $path->prepend($category);
            $category = $category->parent;
        }
        
        return $path;
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include parent categories.
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}