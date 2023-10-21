<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\{AdminLoginRequest, CheckEmailAdminRequest};
use App\Http\Requests\NewPasswordRequest;
use App\Models\Admin;
use App\Services\AuthService\{LoginService, ForgotPasswordService};

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['login', 'forgotPassword', 'resetPassword']]);
    }
    public function login(AdminLoginRequest $request)
    {
        return (new LoginService(new Admin()))->login($request);
    }
    public function logout()
    {
        auth('admin')->logout();
        return response()->json(['message' => 'Admin successfully signed out']);
    }
    public function forgotPassword(CheckEmailAdminRequest $request)
    {
        return (new ForgotPasswordService(new Admin()))->forgotPassword($request);
    }
    public function resetPassword($token, NewPasswordRequest $request)
    {
        return (new ForgotPasswordService(new Admin()))
            ->checkToken($token, $request);
    }
}
