<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\TeacherStatusRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function changeStatus(TeacherStatusRequest $request)
    {
        $teacher = Teacher::find($request->teacher_id);
        $teacher->setAttribute('status', $request->status)->save();
        return response()->json([
            'message' => 'Teacher has been change status'
        ]);
    }
}
