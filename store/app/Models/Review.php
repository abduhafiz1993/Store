<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'pros',
        'cons',
        'status',
        'verified_purchase'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'verified_purchase' => 'boolean',
    ];

    /**
     * Get the user that wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that was reviewed.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the category through product.
     */
    public function category()
    {
        return $this->hasOneThrough(
            Category::class,
            Product::class,
            'id', // Foreign key on products table
            'id', // Foreign key on categories table
            'product_id', // Local key on reviews table
            'category_id' // Local key on products table
        );
    }

    /**
     * Check if review is approved.
     */
    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if review is pending.
     */
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if review is rejected.
     */
    public function getIsRejectedAttribute()
    {
        return $this->status === 'rejected';
    }

    /**
     * Get the rating as stars (for display).
     */
    public function getRatingStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }
        return $stars;
    }

    /**
     * Scope a query to only include approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include pending reviews.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include verified purchase reviews.
     */
    public function scopeVerified($query)
    {
        return $query->where('verified_purchase', true);
    }

    /**
     * Scope a query to filter by rating.
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope a query to filter by minimum rating.
     */
    public function scopeMinRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Scope a query to order by highest rating first.
     */
    public function scopeHighestRated($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    /**
     * Scope a query to order by most recent first.
     */
    public function scopeMostRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to order by most helpful (if you add a helpful votes feature).
     */
    public function scopeMostHelpful($query)
    {
        // This would need a helpful votes table/column
        return $query->orderBy('helpful_votes', 'desc');
    }

    /**
     * Check if the user has already reviewed this product.
     */
    public static function userHasReviewed($userId, $productId)
    {
        return self::where('user_id', $userId)
                   ->where('product_id', $productId)
                   ->exists();
    }

    /**
     * Approve the review.
     */
    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    /**
     * Reject the review.
     */
    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    /**
     * Mark as verified purchase.
     */
    public function markAsVerified()
    {
        $this->update(['verified_purchase' => true]);
    }
}