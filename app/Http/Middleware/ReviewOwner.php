<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Review;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $reviewId = $request->route('review');
        $review = Review::findOrFail($reviewId);
    
        if ($review->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }
    
        return $next($request);
    }
    
}
