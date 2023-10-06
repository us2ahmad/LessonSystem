<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\{CheckEmailStudentRequest, NewPasswordStudentRequest, StudentLoginRequest, StudentRegisterRequest};
use App\Mail\VerificationEmail;
use App\Models\Student;
use App\Services\LoginService\LoginService;
use App\Services\StudentService\StudentRegisterService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:student', ['except' => ['login', 'register', 'forgetpassword', 'checkToken']]);
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
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth('student')->user());
    }
    public function forgetpassword(CheckEmailStudentRequest $request)
    {

        $token = Str::random(32);
        $student = Student::whereEmail($request->email)->first();
        $student->remember_token = $token;
        $student->save();
        Mail::to($request->email)->send(new VerificationEmail($student));
        return response()->json([
            'message' => 'Password reset email sent successfully'
        ]);
    }
    public function checkToken($token, NewPasswordStudentRequest $request)
    {
        $student = Student::whereRememberToken($token)->first();
        return $student
            ? $this->updatepassword($student, $request)
            : response()->json([
                'message' => 'Invalid token'
            ], 400);
    }
    public function updatepassword($student, $request)
    {

        $student->remember_token = null;
        $student->password = Hash::make($request->password);
        $student->save();
        return response()->json([
            'message' => 'password updated'
        ], 200);
    }
}
