<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewPasswordRequest;
use App\Http\Requests\Student\{
    CheckEmailStudentRequest,
    StudentLoginRequest,
    StudentRegisterRequest
};
use App\Services\AuthService\{
    LoginService,
    ForgotPasswordService
};
use App\Services\StudentService\StudentRegisterService;
use App\Models\Student;


class StudentAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:student', ['except' => ['login', 'register', 'resetPassword', 'forgotPassword', 'verify']]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(StudentLoginRequest $request)
    {
        return (new LoginService(new Student(), 'student'))->login($request);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(StudentRegisterRequest $request)
    {
        return (new StudentRegisterService())->register($request);
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('student')->logout();
        return response()->json(['message' => 'Student successfully signed out']);
    }

    public function verify($token)
    {
        $student = Student::whereRememberToken($token)->first();
        if (!$student) {
            return response()->json(['message' => 'The Token is Invalid']);
        }
        $student->remember_token = null;
        $student->email_verified_at = now();
        $student->save();
        return response()->json(['message' => 'Your Account has been verified']);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth('student')->user());
    }
    public function forgotPassword(CheckEmailStudentRequest $request)
    {

        return (new ForgotPasswordService(new Student()))
            ->forgotPassword($request);
    }

    public function resetPassword($token, NewPasswordRequest $request)
    {
        return (new ForgotPasswordService(new Student()))
            ->checkToken($token, $request);
    }
}
