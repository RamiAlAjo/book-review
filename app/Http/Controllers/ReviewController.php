<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Display the form for creating a review for a specific book
    public function create(Book $book)
    {
        // Return the 'create' view, passing the book model instance to it
        return view('books.reviews.create', ['book' => $book]);
    }

    // Store the new review for the specified book
    public function store(Request $request, Book $book)
    {
        // Validate the incoming review data from the form
        $data = $request->validate([
            'review' => 'required|min:15',  // The review text must be at least 15 characters long
            'rating' => 'required|min:1|max:5|integer'  // The rating must be an integer between 1 and 5
        ]);

        // Create a new review associated with the book using the validated data
        $book->reviews()->create($data);

        // Redirect the user back to the book's show page with the new review saved
        return redirect()->route('books.show', $book);
    }
}
