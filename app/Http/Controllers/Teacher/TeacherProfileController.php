<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\UpdatingProfileRequest;
use App\Http\Resources\Teacher\TeacherProfileResource;
use App\Models\PostReview;
use App\Models\Teacher;
use App\Services\TeacherService\UpdatingProfileService;
use Illuminate\Http\Request;

class TeacherProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teacher');
    }

    public function profile()
    {
        $teacherId = auth('teacher')->id();
        $teacher = Teacher::with('posts.reviews')
            ->find($teacherId);
        $reviews = PostReview::whereIn('post_id', $teacher->posts()->pluck('id'))->get();
        $rate = round($reviews->sum('rate') / $reviews->count(), 1);

        return response()->json([
            'data' => [new TeacherProfileResource($teacher), ['rate' => $rate]]
        ]);
    }
    public function edit()
    {
        return response()->json([
            'date' => Teacher::find(auth('teacher')->id())
                ->makeHidden('email_verified_at', 'status', 'created_at', 'updated_at')
        ]);
    }
    public function update(UpdatingProfileRequest $request)
    {
        return (new UpdatingProfileService())->update($request);
    }
    public function delete()
    {
        Teacher::find(auth('teacher')->id())->delete();
        return response()->json(['message' => 'Done Deleted your Acount'], 200);
    }
}
