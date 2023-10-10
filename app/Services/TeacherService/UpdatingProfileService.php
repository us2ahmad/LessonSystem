<?php


namespace  App\Services\TeacherService;

use App\Models\Teacher;
use Illuminate\Http\UploadedFile;

class UpdatingProfileService
{
    protected $model;
    public function __construct()
    {
        $this->model = Teacher::find(auth('teacher')->id());
    }
    public function password($data)
    {
        if (request()->has('password')) {
            $data['password'] = bcrypt(request()->password);
            return $data;
        }
        $data['password'] = $this->model->password;
        return $data;
    }
    public function photo($data)
    {
        if (request()->has('photo')) {
            $data['photo'] = (request()->file('photo') instanceof UploadedFile)
                ? request()->file('photo')->store('teacher')
                : $this->model->photo;
            return $data;
        }
        $data['photo'] = null;
        return $data;
    }
    public function update($request)
    {
        $data = $request->all();
        $data = $this->password($data);
        $data = $this->photo($data);
        $this->model->update($data);
        return response()->json([
            'message' => 'updated'
        ]);
    }
}
