<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $admin = Admin::find(auth()->id());
        return response()->json(
            ['notification' => $admin->notifications]
        );
    }

    public function unread()
    {
        $admin = Admin::find(auth()->id());
        return response()->json(
            ['notification' => $admin->unreadNotifications]
        );
    }
    public function makeReadAll()
    {
        $admin = Admin::find(auth()->id());
        return response()->json(
            ['notification' => $admin->unreadNotifications()->update(['read_at' => now()]), 'message' => 'success']
        );
    }

    public function deleteAll()
    {
        $admin = Admin::find(auth()->id());
        return response()->json(
            ['notification' => $admin->notifications()->delete(), 'message' => 'success deleted']
        );
    }
    public function deleteById($id)
    {
        $admin = Admin::find(auth()->id());
        DB::table('notifications')->where('id', $id)->delete();
        return response()->json(
            ['message' => 'deleted']
        );
    }
}
