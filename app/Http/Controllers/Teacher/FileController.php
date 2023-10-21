<?php

namespace App\Http\Controllers\Teacher;

use App\Exports\PostsExport;
use App\Http\Controllers\Controller;
use App\Imports\PostsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class FileController extends Controller
{
    public function export()
    {
        return Excel::download(new PostsExport, 'posts.xlsx');
    }
    public function import()
    {
        Excel::import(new PostsImport, request()->file('file'));

        return response()->json([
            'message' => 'add files done'
        ]);
    }
}
