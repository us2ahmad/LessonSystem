<?php

namespace  App\Services\AuthService;

use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordService
{
    protected $model;
    public function __construct($model)
    {
        $this->model = $model;
    }
    public function forgotpassword($request)
    {
        $user = $this->model->whereEmail($request->email)->first();
        $token = Str::random(32);
        $user->password_token = $token;
        $user->save();
        Mail::to($request->email)->send(new VerificationEmail($user));
        return response()->json([
            'message' => 'Password reset email sent successfully'
        ]);
    }
    public function checkToken($token, $request)
    {
        $user = $this->model->wherePasswordToken($token)->first();
        return $user
            ? $this->updatepassword($user, $request)
            : response()->json([
                'message' => 'Invalid token'
            ], 400);
    }
    public function updatepassword($user, $request)
    {
        $user->password_token = null;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'message' => 'password updated'
        ], 200);
    }
}
