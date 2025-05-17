@extends('layouts.app')

@section('content')
  <!-- Book Title and Author Section -->
  <div class="mb-4">
    <!-- Display the book title -->
    <h1 class="mb-2 text-2xl">{{ $book->title }}</h1>

    <!-- Book Information Section (Author and Rating) -->
    <div class="book-info">
      <!-- Display the book author with some styling -->
      <div class="book-author mb-4 text-lg font-semibold">by {{ $book->author }}</div>

      <!-- Book Rating and Review Count Section -->
      <div class="book-rating flex items-center">
        <!-- Star rating component to show the average rating -->
        <div class="mr-2 text-sm font-medium text-slate-700">
          <x-star-rating :rating="$book->reviews_avg_rating" />
        </div>

        <!-- Display the review count, making "review" plural if necessary -->
        <span class="book-review-count text-sm text-gray-500">
          {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
        </span>
      </div>
    </div>
  </div>

  <!-- Button to add a new review for the book -->
  <div class="mb-4">
    <a href="{{ route('books.reviews.create', $book) }}" class="reset-link">
      Add a review!</a>
  </div>

  <!-- Reviews Section -->
  <div>
    <!-- Heading for the reviews section -->
    <h2 class="mb-4 text-xl font-semibold">Reviews</h2>

    <!-- Loop through all the reviews for the book -->
    <ul>
      @forelse ($book->reviews as $review)
        <li class="book-item mb-4">
          <div>
            <!-- Review Rating and Date -->
            <div class="mb-2 flex items-center justify-between">
              <div class="font-semibold">
                <!-- Star rating for each individual review -->
                <x-star-rating :rating="$review->rating" />
              </div>

              <!-- Display the review's creation date -->
              <div class="book-review-count">
                {{ $review->created_at->format('M j, Y') }}</div>
            </div>

            <!-- Display the text of the review -->
            <p class="text-gray-700">{{ $review->review }}</p>
          </div>
        </li>
      @empty
        <!-- If no reviews are available, show a message -->
        <li class="mb-4">
          <div class="empty-book-item">
            <p class="empty-text text-lg font-semibold">No reviews yet</p>
          </div>
        </li>
      @endforelse
    </ul>
  </div>
@endsection
