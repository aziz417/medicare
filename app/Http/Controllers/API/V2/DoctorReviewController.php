<?php

namespace App\Http\Controllers\API\V2;

use App\Models\User;
use App\Models\DoctorReview;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;

class DoctorReviewController extends Controller
{
    public function index(User $doctor, Request $request)
    {
        $reviews = DoctorReview::where('user_id', $doctor->id)->paginate(15);
        return new ApiResponse([
            'items' => $reviews->map(function($item){
                return $item->getPublicData();
            }),
            'next' => $reviews->nextPageUrl()
        ]);
    }

    public function store(User $doctor, Request $request)
    {
        $request->validate([
            'rating' => 'required',
            'details' => 'nullable|string',
        ]);
        $review = $doctor->reviews()->create([
            'review_by' => auth()->id(),
            'rating' => (int) $request->rating,
            'details' => $request->details,
        ]);
        if( !$review ){
            return new ApiResponse("Something went wrong, try again!", 422);
        }
        return new ApiResponse($review->getPublicData(), 201, "Review submitted successfully!");
    }
}
