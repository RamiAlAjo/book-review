@extends('layouts.app')

@section('content')
  <!-- Page Title: Add Review for a Specific Book -->
  <h1 class="mb-10 text-2xl">Add Review for {{ $book->title }}</h1>

  <!-- Review Form -->
  <form method="POST" action="{{ route('books.reviews.store', $book) }}">
    <!-- CSRF token for form submission protection -->
    @csrf

    <!-- Textarea for writing the review -->
    <label for="review">Review</label>
    <textarea name="review" id="review" required class="input mb-4"></textarea>

    <!-- Dropdown for selecting the rating -->
    <label for="rating">Rating</label>
    <select name="rating" id="rating" class="input mb-4" required>
      <!-- Default option for selecting a rating -->
      <option value="">Select a Rating</option>

      <!-- Loop to generate rating options (1 through 5 stars) -->
      @for ($i = 1; $i <= 5; $i++)
        <option value="{{ $i }}">{{ $i }}</option>
      @endfor
    </select>

    <!-- Submit button to add the review -->
    <button type="submit" class="btn">Add Review</button>
  </form>
@endsection
