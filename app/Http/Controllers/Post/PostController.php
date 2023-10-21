<?php

namespace App\Http\Controllers\Post;

use App\Filters\PostFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StoringPostRequest;
use App\Http\Resources\Post\ApprovedPostResource;
use App\Http\Resources\Post\ShowAllPostsResource;
use App\Models\Post;
use App\Services\PostService\StoringPostService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('photos')->get();
        return ShowAllPostsResource::collection($posts);
    }

    public function store(StoringPostRequest $request)
    {
        return (new StoringPostService())->store($request);
    }

    public function deleteById($id)
    {

        $post =  Post::where('id', $id)
            ->where('teacher_id', auth('teacher')->id())
            ->delete();
        return $post ?
            response()->json([
                'message' => 'deleted success'
            ])
            :  response()->json([
                'message' => 'not found'
            ]);
    }

    public function showPostsApproved()
    {
        $posts =    QueryBuilder::for(Post::class)
            ->allowedFilters((new PostFilter())->filter())
            ->whereStatus('approved')
            ->with('photos')
            ->with('teacher')
            ->get();

        return count($posts) > 0
            ? ApprovedPostResource::collection($posts)
            : response()->json([
                'message' => 'not found'
            ]);
    }

    public function allDataPost($id)
    {
        $post = Post::with('photos')
            ->whereStatus('approved')
            ->where('id', $id)
            ->first();
        return ($post)
            ? new ApprovedPostResource($post)
            : response()->json([
                'message' => 'not found'
            ]);
    }
}
