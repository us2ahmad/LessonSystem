<?php

namespace App\Services\StudentService;

use App\Mail\VerificationEmail;
use App\Models\Student;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class StudentRegisterService
{

    protected $model;
    public function __construct()
    {
        $this->model = new Student();
    }
    function validation($request)
    {
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json(
                $validator->error(),
                422
            );
        }
        return $validator;
    }
    function store($data, $request)
    {
        $student = $this->model->create(
            array_merge(
                $data->validated(),
                [
                    'password' => bcrypt($request->password),
                    'photo' => $request->file('photo')->store('student')
                ]
            )
        );
        return $student->email;
    }
    function generateToken($email)
    {
        $token =  substr(md5(rand(0, 9) . $email . time()), 0, 32);
        $student = $this->model->whereEmail($email)->first();
        $student->remember_token = $token;
        $student->save();
        return $student;
    }
    function sendEmail($student)
    {
        Mail::to($student->email)->send(new VerificationEmail($student));
    }
    function register($request)
    {
        try {
            DB::beginTransaction();
            $data = $this->validation($request);
            $email = $this->store($data, $request);
            $student = $this->generateToken($email);
            $this->sendEmail($student);
            DB::commit();
            return response()->json(['message' => 'Account has been created please check your Email']);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
