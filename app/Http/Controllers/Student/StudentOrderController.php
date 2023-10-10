<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudentOrderRequest;
use App\Interfaces\CrudRepoInterface;
use App\Models\StudentOrder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentOrderController extends Controller
{
    protected $repo;
    public function __construct(CrudRepoInterface $repo)
    {

        $this->repo = $repo;
        $this->middleware('auth:student');
    }
    public function addOrder(StudentOrderRequest $request)
    {
        return $this->repo->store($request);
    }
}
