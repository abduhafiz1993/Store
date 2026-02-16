<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'compare_price',
        'quantity',
        'sku',
        'status',
        'category_id',
        'attributes',
        'tags',
        'views_count',
        'sales_count'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'quantity' => 'integer',
        'attributes' => 'array',
        'tags' => 'array',
        'views_count' => 'integer',
        'sales_count' => 'integer',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the approved reviews for the product.
     */
    public function approvedReviews()
    {
        return $this->reviews()->where('status', 'approved');
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the wishlists for the product.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the users who have this product in their wishlist.
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    /**
     * Calculate the average rating for the product.
     */
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Get the total number of approved reviews.
     */
    public function getReviewsCountAttribute()
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Check if product has discount.
     */
    public function getHasDiscountAttribute()
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }

    /**
     * Calculate discount percentage.
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount) {
            return 0;
        }
        
        return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    /**
     * Check if product is in stock.
     */
    public function getInStockAttribute()
    {
        return $this->quantity > 0;
    }

    /**
     * Check if product is new (less than 30 days old).
     */
    public function getIsNewAttribute()
    {
        return $this->created_at->diffInDays(now()) < 30;
    }

    /**
     * Get the product's minimum price (for variable products).
     */
    public function getMinPriceAttribute()
    {
        // If you have variations table, you'd calculate from there
        return $this->price;
    }

    /**
     * Get the product's maximum price (for variable products).
     */
    public function getMaxPriceAttribute()
    {
        // If you have variations table, you'd calculate from there
        return $this->price;
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include products in stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Scope a query to only include products on sale.
     */
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('compare_price')
                     ->whereColumn('compare_price', '>', 'price');
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopePriceBetween($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to search products.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%")
                     ->orWhere('description', 'LIKE', "%{$term}%")
                     ->orWhere('sku', 'LIKE', "%{$term}%")
                     ->orWhere('tags', 'LIKE', "%{$term}%");
    }

    /**
     * Scope a query to order by price low to high.
     */
    public function scopePriceLowToHigh($query)
    {
        return $query->orderBy('price', 'asc');
    }

    /**
     * Scope a query to order by price high to low.
     */
    public function scopePriceHighToLow($query)
    {
        return $query->orderBy('price', 'desc');
    }

    /**
     * Scope a query to order by newest first.
     */
    public function scopeNewest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to order by best selling.
     */
    public function scopeBestSelling($query)
    {
        return $query->orderBy('sales_count', 'desc');
    }

    /**
     * Scope a query to order by most viewed.
     */
    public function scopeMostViewed($query)
    {
        return $query->orderBy('views_count', 'desc');
    }

    /**
     * Scope a query to order by highest rated.
     */
    public function scopeHighestRated($query)
    {
        return $query->withAvg('approvedReviews', 'rating')
                     ->orderBy('approved_reviews_avg_rating', 'desc');
    }

    /**
     * Increment views count.
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Decrement quantity when ordered.
     */
    public function decrementQuantity($amount = 1)
    {
        if ($this->quantity >= $amount) {
            $this->decrement('quantity', $amount);
            $this->increment('sales_count', $amount);
            return true;
        }
        
        return false;
    }
}