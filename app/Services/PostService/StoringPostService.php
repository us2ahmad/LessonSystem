<?php

namespace App\Services\PostService;

use App\Models\Admin;
use App\Models\Post;
use App\Models\PostPhoto;
use App\Notifications\AdminPost;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class StoringPostService
{

    protected $model;
    public function __construct()
    {
        $this->model = new Post();
    }
    function storePost($request)
    {
        $data = $request->except('photos');
        $data['teacher_id'] = auth('teacher')->id();
        $post = Post::create($data);
        return $post;
    }
    function storePostPhotos($request, $post_id)
    {
        foreach ($request->file('photos') as $photo) {
            $postPhoto = new PostPhoto();
            $postPhoto->post_id = $post_id;
            $postPhoto->photo = $photo->store('posts');
            $postPhoto->save();
        }
    }
    function sendAdminNotification($post)
    {
        $admins = Admin::all();
        Notification::send($admins, new AdminPost($post, auth('teacher')->user()));
    }
    function store($request)
    {
        try {
            DB::beginTransaction();
            $post = $this->storePost($request);
            if ($request->hasFile('photos')) {

                $postPhoto = $this->storePostPhotos($request, $post->id);
            }
            $this->sendAdminNotification($post);
            DB::commit();
            return response()->json(['message' => 'post has been created successfuly']);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
