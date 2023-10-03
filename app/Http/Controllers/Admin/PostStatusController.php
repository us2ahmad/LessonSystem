<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PostStatusRequest;
use App\Models\Post;
use App\Notifications\AdminPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PostStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function changeStatus(PostStatusRequest $request)
    {
        $post = Post::find($request->post_id);
        $post->update([
            'status' => $request->status,
            'rejected_reason' => $request->rejected_reason
        ]);
        Notification::send($post->teacher, new AdminPost($post, $post->teacher));
        return response()->json([
            'message' => 'post has been change status'
        ]);
    }
}
