<?php

namespace  App\Services\TeacherService;

use App\Mail\VerificationEmail;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class TeacherRegisterService
{

    protected $model;
    public function __construct()
    {
        $this->model = new Teacher();
    }

    function validation($request)
    {
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return $validator;
    }



    function store($request, $data)
    {
        $teacher = $this->model->create(array_merge(
            $data->validated(),
            [
                'password' => bcrypt($request->password),
                'photo' => $request->file('photo')->store('teacher'),
            ]
        ));
        return $teacher->email;
    }

    function generateToken($email)
    {
        $token =  substr(md5(rand(0, 9) . $email . time()), 0, 32);
        $teacher = $this->model->whereEmail($email)->first();
        $teacher->remember_token = $token;
        $teacher->save();
        return $teacher;
    }
    function sendEmail($teacher)
    {
        Mail::to($teacher->email)->send(new VerificationEmail($teacher));
    }

    function register($request)
    {
        try {
            DB::beginTransaction();
            $data = $this->validation($request);
            $email = $this->store($request, $data);
            $teacher = $this->generateToken($email);
            // $this->sendEmail($teacher);
            DB::commit();
            return response()->json([
                'message' => 'Account has been created please check your Email',
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
            DB::rollBack();
        }
    }
}
