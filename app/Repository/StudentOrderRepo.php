<?php

namespace App\Repository;

use App\Interfaces\CrudRepoInterface;
use App\Models\StudentOrder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class StudentOrderRepo implements CrudRepoInterface
{
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['student_id'] = auth('student')->id();
            $order = StudentOrder::create($data);
            DB::commit();
            return response()->json([
                'message' => 'success'
            ], 201);
        } catch (UniqueConstraintViolationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Duplicate Request'
            ], 400);
        }
    }
}
