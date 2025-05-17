<?php

// Import necessary controllers and classes
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Redirect the root URL ("/") to the 'books.index' route
Route::get('/', function () {
    return redirect()->route('books.index');  // Redirects to the books index page
});

// Define resource routes for the 'books' resource, but only include the 'index' and 'show' methods
Route::resource('books', controller: BookController::class)
    ->only(['index', 'show']); // The 'index' method shows a list of books, and 'show' shows a single book

// Route to display the form for creating a review for a specific book
Route::get('books/{book}/reviews/create', [ReviewController::class, 'create'])
    ->name('books.reviews.create');  // This will show the review creation form for the specific book

// Route to handle storing the review for a specific book
Route::post('books/{book}/reviews', [ReviewController::class, 'store'])
    ->name('books.reviews.store')  // This will store the review for the specific book
    ->middleware('throttle:reviews'); // Apply a throttle to limit the number of reviews that can be submitted in a given time period
