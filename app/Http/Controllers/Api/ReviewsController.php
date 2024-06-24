<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    //add middleware to update and destroy only
    public function __construct()
    {
        $this->middleware('reviewowner')->only(['update', 'destroy']);
    }

    //show all review
    public function index()
    {
        //get review
        $review = Review::with(['reviewer'])->get();

        //return collection of reviews as a resource
        return ReviewResource::collection($review);

    }
    //create review
    public function store(Request $request)
    {
        //check if the user already login or not
        if (!auth()->check()) {
            return response()->json(['message' => 'Harus Login Dahulu!']);
        }
        //fill the review
        $validated = $request->validate([
            'app_id' => 'required|exists:applications,id',
            'review_text' => 'required|string',
            'rating' => 'required|numeric|min:0|max:5',
        ]);
        //find user id
        $request['user_id']=auth()->user()->id;
        $review = Review::create($request->all());
        //returne response
        return (new ReviewResource($review))->additional(['message' => 'Berhasil Ditambahkan!']);
    }
    public function show(Review $review)
    {
        return ReviewResource::collection($review);
    }

    public function edit(Review $review)
    {
        //
    }

    //update review
    public function update(Request $request, $id)
    {
        //fill the updated review
        $validate=$request->validate([
            'review_text'=>'required',
            'rating'=>'required|numeric|min:0|max:5',
        ]);

        //find review 
        $review= Review::findOrFail($id);
        $review->update($request->only(['review_text','rating']));
        //return response
        return (new ReviewResource($review))->additional(['message' => 'Berhasil diupdate!']);

    }

    //delete review
    public function destroy($id)
    {
        //find review
        $review = Review::findOrFail($id);
        //delete review
        $review->delete();
        //return response
        return (new ReviewResource($review))->additional(['message' => 'Berhasil dihapus!']);

    }
}
