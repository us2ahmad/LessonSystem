<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudentOrder;
use Illuminate\Http\Request;

class TeacherOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teacher');
    }

    public function showOrder()
    {
        $orders = StudentOrder::with('post', 'student')
            ->whereStatus('pending')
            ->whereHas('post', function ($query) {
                $query->where('teacher_id', auth('teacher')->id());
            })
            ->get();
        return response()->json([
            'orders' => $orders
        ]);
    }

    public  function updateStatus(Request $request)
    {
        $order = StudentOrder::where('id', $request->order_id)
            ->whereHas('post', function ($query) {
                $query->where('teacher_id', auth('teacher')->id());
            })->first();

        if ($order) {
            $order->setAttribute('status', $request->status)->save();
            return   response()->json([
                'message' => 'updated'
            ]);
        } else {
            return  response()->json([
                'message' => 'Can\'t updated'
            ]);
        }
    }
}
