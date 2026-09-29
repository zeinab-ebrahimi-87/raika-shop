<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'برای ثبت نظر باید وارد بشی.');
        }
        
        $product = Product::findOrFail($productId);
        
        // چک کن کاربر قبلاً نظر نداده
        $existing = Review::where('product_id', $productId)
            ->where('user_id', Auth::id())
            ->first();
        
        if ($existing) {
            return back()->with('error', 'تو قبلاً برای این محصول نظر دادی.');
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ], [
            'rating.required' => 'امتیاز الزامی است.',
            'rating.min' => 'حداقل امتیاز ۱ ستاره است.',
            'rating.max' => 'حداکثر امتیاز ۵ ستاره است.',
            'comment.required' => 'متن نظر الزامی است.',
            'comment.min' => 'نظر باید حداقل ۵ کاراکتر باشد.',
            'comment.max' => 'نظر حداکثر ۱۰۰۰ کاراکتر.',
        ]);
        
        Review::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // نیاز به تأیید ادمین
        ]);
        
        return back()->with('success', 'نظرت ثبت شد و بعد از تأیید نمایش داده میشه. ممنون! 🌟');
    }
    
    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403);
        }
        
        $review = Review::findOrFail($id);
        $review->delete();
        
        return back()->with('success', 'نظر حذف شد.');
    }
    
    public function approve($id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403);
        }
        
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => true]);
        
        return back()->with('success', 'نظر تأیید شد.');
    }

    
}