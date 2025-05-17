@extends('layouts.app')

@section('content')
  <!-- Page title -->
  <h1 class="mb-10 text-2xl">Books</h1>

  <!-- Search form for filtering books by title -->
  <form method="GET" action="{{ route('books.index') }}" class="mb-4 flex items-center space-x-2">
    <!-- Input field for title search, preserving the previous search term -->
    <input type="text" name="title" placeholder="Search by title"
      value="{{ request('title') }}" class="input h-10" />

    <!-- Hidden input to carry over the current filter state when submitting the form -->
    <input type="hidden" name="filter" value="{{ request('filter') }}" />

    <!-- Submit button for the search form -->
    <button type="submit" class="btn h-10">Search</button>

    <!-- Clear button to reset the search query and filters -->
    <a href="{{ route('books.index') }}" class="btn h-10">Clear</a>
  </form>

  <!-- Filter options container -->
  <div class="filter-container mb-4 flex">
    @php
      // Defining the possible filters for the books
      $filters = [
          '' => 'Latest', // Default filter: Latest books
          'popular_last_month' => 'Popular Last Month', // Filter: Popular books in the last month
          'popular_last_6months' => 'Popular Last 6 Months', // Filter: Popular books in the last 6 months
          'highest_rated_last_month' => 'Highest Rated Last Month', // Filter: Highest rated books in the last month
          'highest_rated_last_6months' => 'Highest Rated Last 6 Months', // Filter: Highest rated books in the last 6 months
      ];
    @endphp

    <!-- Loop through each filter option and create clickable links -->
    @foreach ($filters as $key => $label)
      <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}"
        class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}">
        {{ $label }}
      </a>
    @endforeach
  </div>

  <!-- List of books -->
  <ul>
    <!-- Loop through each book and display its details -->
    @forelse ($books as $book)
      <li class="mb-4">
        <div class="book-item">
          <div class="flex flex-wrap items-center justify-between">
            <div class="w-full flex-grow sm:w-auto">
              <!-- Link to view the book's details -->
              <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
              <span class="book-author">by {{ $book->author }}</span>
            </div>
            <div>
              <!-- Display the book's average rating using a custom component (e.g., star rating) -->
              <div class="book-rating">
                <x-star-rating :rating="$book->reviews_avg_rating" />
              </div>
              <!-- Show the number of reviews the book has -->
              <div class="book-review-count">
                out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
              </div>
            </div>
          </div>
        </div>
      </li>
    @empty
      <!-- If no books are found, display a message -->
      <li class="mb-4">
        <div class="empty-book-item">
          <p class="empty-text">No books found</p>
          <!-- Link to reset the filters and show all books again -->
          <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
        </div>
      </li>
    @endforelse
  </ul>
@endsection
