<?php

namespace  App\Services\TeacherService\TeacherLoginService;

use App\Models\Teacher;
use Illuminate\Support\Facades\Validator;

class TeacherLoginService{
 
    protected $model;
    public function __construct()
    {
        $this->model= new Teacher();
    } 

     function validation($request){
       $validator = Validator::make($request->all(),$request->rules());
       if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }
        return $validator;
    }

    function isValiData($data){
        if (! $token = auth('teacher')->attempt($data->validated())) {
            return response()->json(['error' => 'invalid data'], 401);
        }
        return $token;
    }

    function getStatus($email){
      $teacher =  $this->model->whereEmail($email)->first();
      $status=$teacher->status;
      return $status;

    }
    function isVerified($email){
      $teacher =  $this->model->whereEmail($email)->first();
      $verified=$teacher->email_verified_at;
      return $verified;

    }


     function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth('teacher')->user()
        ]);
    }

    function login($request){
        $data=$this->validation($request);
        $token=$this->isValiData($data);
       
        if($this->isVerified($request->email)==null){
            return response()->json(['message' => 'your Account is not Verified'], 422);
        } 
        elseif($this->getStatus($request->email)==0){
            return response()->json(['message' => 'Account is Pending'], 422);
        }
      ;
            return $this->createNewToken($token);
    }
}