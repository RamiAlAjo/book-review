<?php

namespace App\Http\Controllers;

use App\Models\Book;  // Import the Book model for interacting with the database
use Illuminate\Http\Request;  // Import the Request class to handle HTTP requests

class BookController extends Controller
{
    // This method handles the logic for displaying a list of books, optionally filtered by title or other criteria.
    public function index(Request $request)
    {
        // Retrieve the 'title' parameter from the request (if provided)
        $title = $request->input('title');
        
        // Retrieve the 'filter' parameter from the request, defaulting to an empty string if not provided
        $filter = $request->input('filter', '');

        // Start a query on the Book model
        $books = Book::when(
            $title, // If 'title' is provided in the request
            fn($query, $title) => $query->title($title) // Apply a filter on the 'title' field of books
        );

        // Apply different filters based on the value of 'filter' in the request
        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(), // Get popular books from the last month
            'popular_last_6months' => $books->popularLast6Months(), // Get popular books from the last 6 months
            'highest_rated_last_month' => $books->highestRatedLastMonth(), // Get highest rated books from the last month
            'highest_rated_last_6months' => $books->highestRatedLast6Months(), // Get highest rated books from the last 6 months
            default => $books->latest()->withAvgRating()->withReviewsCount() // Default: Get latest books with average rating and reviews count
        };

        // Execute the query and get the results (no caching here)
        $books = $books->get();

        // Return the 'books.index' view, passing the retrieved books to the view
        return view('books.index', ['books' => $books]);
    }

    // This method renders the form for creating a new book (currently not implemented)
    public function create()
    {
        //
    }

    // This method handles the logic for storing a newly created book (currently not implemented)
    public function store(Request $request)
    {
        //
    }

    // This method displays details for a specific book, including reviews and ratings
    public function show(int $id)
    {
        // Fetch the book with the given ID, along with its reviews (ordered by the latest reviews)
        // Also, retrieve the average rating and review count for the book
        $book = Book::with([
            'reviews' => fn($query) => $query->latest() // Order reviews by latest
        ])->withAvgRating()->withReviewsCount()->findOrFail($id); // Fetch the book, or fail if not found

        // Return the 'books.show' view, passing the retrieved book to the view
        return view('books.show', ['book' => $book]);
    }

    // This method renders the form for editing an existing book (currently not implemented)
    public function edit(string $id)
    {
        //
    }

    // This method handles the logic for updating an existing book (currently not implemented)
    public function update(Request $request, string $id)
    {
        //
    }

    // This method handles the logic for deleting an existing book (currently not implemented)
    public function destroy(string $id)
    {
        //
    }
}
