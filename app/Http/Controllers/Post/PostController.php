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

        Post::find($id)->delete();
        return response()->json([
            'message' => 'deleted success'
        ]);
    }

    public function showPostsApproved()
    {
        $posts =    QueryBuilder::for(Post::class)
            ->allowedFilters((new PostFilter())->filter())
            ->with('photos')->with('teacher')
            ->whereStatus('approved')->get();
        return ApprovedPostResource::collection($posts);
    }

    public function allDataPost($id)
    {
        $post = Post::with('photos')
            ->whereStatus('approved')
            ->where('id', $id)
            ->first();
        return  new ApprovedPostResource($post);
    }
}
