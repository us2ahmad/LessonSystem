<?php

namespace  App\Services\PayService;

use App\Models\Payment;
use App\Models\Post;
use App\Models\StudentOrder;
use Exception;
use Illuminate\Support\Facades\DB;

class PayService
{
    function checkStudentOrder($postId)
    {
        $stdOrder = StudentOrder::Where('post_id', $postId)
            ->where('student_id', auth('student')->id())
            ->where('status', 'approved')
            ->first();
        return $stdOrder->post_id;
    }
    function getPost($postId)
    {
        $post = Post::find($postId);
        return $post;
    }
    function pay($post)
    {
        $link = auth('student')->user()->charge($post->price, $post->content);
        return $link;
    }
    function storePay($post)
    {
        Payment::create([
            'post_id' => $post->id,
            'student_id' => auth('student')->id(),
            'total' => $post->price
        ]);
    }
    public function payment($servicesId)
    {
        try {
            DB::beginTransaction();
            $postId = $this->checkStudentOrder($servicesId);
            $post = $this->getPost($postId);
            $link = $this->pay($post);
            $this->storePay($post);
            DB::commit();
            return response()->json([
                'link' => $link
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}
