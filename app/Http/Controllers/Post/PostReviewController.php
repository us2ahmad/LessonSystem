<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PostReviewRequest;
use App\Http\Resources\Post\PostReviewResource;
use App\Models\Post;
use App\Models\PostReview;

class PostReviewController extends Controller
{
    public function review(PostReviewRequest $request)
    {

        $studentId = auth('student')->id();
        if (PostReview::where('post_id', $request->post_id)->where('student_id', $studentId)->exists()) {

            return response()->json([
                'message' => 'duplicate review'
            ]);
        }
        $data = $request->all();
        $data['student_id'] = $studentId;
        $postReview = PostReview::create($data);
        return response()->json([
            'message' => $postReview
        ]);
    }
    public function postRate($id)
    {
        $post = Post::find($id);
        if ($post) {
            $reviews = PostReview::wherePostId($id);
            $avg = $reviews->sum('rate') / $reviews->count();
            return response()->json([
                'total_rate' => round($avg, 1),
                'data' => PostReviewResource::collection($reviews->get())
            ]);
        }
        return response()->json([
            'message' => 'not found'
        ]);
    }
}
