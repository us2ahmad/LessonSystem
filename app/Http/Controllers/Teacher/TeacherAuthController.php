<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewPasswordRequest;
use App\Http\Requests\Teacher\{
    TeacherLoginRequest,
    CheckEmailTeacherRequest,
    TeacherRegisterRequest
};
use App\Models\Teacher;
use App\Services\AuthService\{
    ForgotPasswordService,
    LoginService
};
use App\Services\TeacherService\TeacherRegisterService;

class TeacherAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:teacher', ['except' => ['login', 'register', 'verify', 'resetPassword', 'forgotPassword']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(TeacherLoginRequest $request)
    {

        return (new LoginService(new Teacher(), 'teacher'))->login($request);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(TeacherRegisterRequest $request)
    {

        return (new TeacherRegisterService())->register(($request));
    }

    public function verify($token)
    {
        $teacher = Teacher::whereRememberToken($token)->first();
        if (!$teacher) {
            return response()->json(['message' => 'The Token is Invalid']);
        }
        $teacher->remember_token = null;
        $teacher->email_verified_at = now();
        $teacher->save();
        return response()->json(['message' => 'Your Account has been verified']);
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('teacher')->logout();
        return response()->json(['message' => 'Teacher successfully signed out']);
    }
    public function forgotPassword(CheckEmailTeacherRequest $request)
    {
        return (new ForgotPasswordService(new Teacher()))
            ->forgotPassword($request);
    }

    public function resetPassword($token, NewPasswordRequest $request)
    {
        return (new ForgotPasswordService(new Teacher()))
            ->checkToken($token, $request);
    }
}
