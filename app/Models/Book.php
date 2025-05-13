<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Book extends Model
{
    use HasFactory;

    // Define a relationship: Each book can have many reviews
    public function reviews()
    {
        /* This means that each book has many reviews.
           It establishes a one-to-many relationship with the 'Review' model. */
        return $this->hasMany(Review::class);
    }

    // Scope to filter books based on popularity (number of reviews)
    public function scopePopular(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withReviewsCount() // Load the count of reviews for each book
            ->orderBy('reviews_count', 'desc'); // Sort books by reviews count in descending order
    }

    // Scope to filter books based on average rating
    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvgRating() // Load the average rating for each book
            ->orderBy('reviews_avg_rating', 'desc'); // Sort books by average rating in descending order
    }

    // Scope to filter books based on minimum number of reviews
    public function scopeMinReviews(Builder $query, int $minReviews): Builder|QueryBuilder
    {
        return $query->having('review_count', '>=', $minReviews); // Only books with at least $minReviews reviews
    }

    // Private helper function to apply date range filter (used in multiple scopes)
    private function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        if ($from && !$to) {
            // If only $from is provided, filter books created after this date
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            // If only $to is provided, filter books created before this date
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            // If both $from and $to are provided, filter books created within this range
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    // Scope to filter books by their title using a "like" search
    public function scopeTitle($query, $title)
    {
        return $query->where('title', 'like', "%$title%"); // Filter books where the title contains the $title string
    }

    // Scope for popular books from the last month
    public function scopePopularLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonth(), now()) // Apply popularity filter for the last month
            ->highestRated(now()->subMonth(), now()) // Apply highest rating filter for the last month
            ->minReviews(2); // Ensure books have at least 2 reviews
    }

    // Scope for popular books from the last 6 months
    public function scopePopularLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonths(6), now()) // Apply popularity filter for the last 6 months
            ->highestRated(now()->subMonths(6), now()) // Apply highest rating filter for the last 6 months
            ->minReviews(5); // Ensure books have at least 5 reviews
    }

    // Scope for highest-rated books from the last month
    public function scopeHighestRatedLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonth(), now()) // Apply highest rating filter for the last month
            ->popular(now()->subMonth(), now()) // Apply popularity filter for the last month
            ->minReviews(2); // Ensure books have at least 2 reviews
    }

    // Scope for highest-rated books from the last 6 months
    public function scopeHighestRatedLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonths(6), now()) // Apply highest rating filter for the last 6 months
            ->popular(now()->subMonths(6), now()) // Apply popularity filter for the last 6 months
            ->minReviews(5); // Ensure books have at least 5 reviews
    }
}
