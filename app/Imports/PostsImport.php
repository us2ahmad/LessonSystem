<?php

namespace App\Imports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\ToModel;

class PostsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Post([
            'id'     => $row[0],
            'teacher_id'    => $row[1],
            'content' =>   $row[2],
            'price' =>  $row[3],
            'status' => $row[4],
            'rejected_reason' => $row[5],
            'created_at' => $row[6],
            'updated_at' => $row[7],
        ]);
    }
}
