<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $pendingReviews = Review::with(['product', 'user'])
            ->where('is_approved', false)
            ->latest()
            ->get();
        
        $approvedReviews = Review::with(['product', 'user'])
            ->where('is_approved', true)
            ->latest()
            ->paginate(15);
        
        return view('admin.reviews.index', compact('pendingReviews', 'approvedReviews'));
    }
    
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => true]);
        
        return back()->with('success', 'نظر تأیید شد.');
    }
    
    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        
        return back()->with('success', 'نظر رد و حذف شد.');
    }
    
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        
        return back()->with('success', 'نظر حذف شد.');
    }
}