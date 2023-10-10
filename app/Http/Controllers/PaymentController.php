<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function pay()
    {

        $link = auth('student')->user()->charge(12.99, 'Action Figure');
        return response()->json([
            'link' => $link
        ]);
    }
    // public function pay()
    // {

    //     $link = Student::find(1)->charge(12.99, 'Action Figure');
    //     return view('pay', compact('link'));
    // }
}
