<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve all books from the database initially
        $books = Book::all();

        // Retrieve the 'title' query parameter from the request, if any
        $title = $request->input('title');

        // Retrieve the 'filter' query parameter from the request (default to an empty string if not provided)
        $filter = $request->input('filter', '');

        // If a 'title' is provided, filter the books based on the 'title' field
        // This makes use of the 'when' method to conditionally apply filters
        $books = Book::when(
            $title,
            fn($query, $title) => $query->title($title) // Apply a custom 'title' filter scope
        );

        // Uncommented code below would have used a traditional 'like' query to filter books by title
        // This is commented out because the previous method is a more optimized approach (using scopes)
        // $books = Book::when(
        //     $title,
        //     fn($query, $title) => $query->where('title', 'like', "%$title%")
        // )->get();

        // Apply additional filtering based on the 'filter' parameter
        // Using 'match' to apply different filtering methods depending on the filter value
        // Each case in the match statement represents a different filter option
        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(), // Custom scope to get popular books in the last month
            'popular_last_6months' => $books->popularLast6Months(), // Custom scope for popular books in the last 6 months
            'highest_rated_last_month' => $books->highestRatedLastMonth(), // Custom scope for highest rated books last month
            'highest_rated_last_6months' => $books->highestRatedLast6Months(), // Custom scope for highest rated books in the last 6 months
            default => $books->latest()->withAvgRating()->withReviewsCount() // Default case: get the latest books with average rating and review count
        };

        // Create a unique cache key based on the filter and title parameters to store the results
        $cacheKey = 'books:' . $filter . ':' . $title;

        // Check if the filtered books are already cached, otherwise, fetch the books and store them in cache for 1 hour (3600 seconds)
        $books = cache()->remember(
            $cacheKey, // Cache key based on filter and title
            3600, // Cache duration in seconds (1 hour)
            fn() => $books->get() // If not cached, execute the query to retrieve the books from the database
        );

        // Return the view with the filtered books
        return view('books.index', compact('books'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
