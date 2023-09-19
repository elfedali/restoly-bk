<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewReview;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewStoreRequest;
use App\Http\Requests\Admin\ReviewUpdateRequest;
use App\Mail\NewReviewMail;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $reviews = Review::all();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Request $request, Review $review): View
    {
        return view('admin.reviews.show', compact('review'));
    }

    public function create(Request $request): View
    {
        return view('admin.reviews.create');
    }

    public function store(ReviewStoreRequest $request): RedirectResponse
    {
        $review = Review::create($request->validated());

        event(new NewReview($review));

        Mail::to($review->email)->send(new NewReviewMail($review));

        $request->session()->flash('review.name', $review->name);

        return redirect()->route('admin.reviews.index');
    }

    public function edit(Request $request, Review $review): View
    {
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(ReviewUpdateRequest $request, Review $review): RedirectResponse
    {
        $review->save();

        return redirect()->route('admin.reviews.index');
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index');
    }
}
