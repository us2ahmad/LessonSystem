<?php

namespace App\Http\Controllers;

use App\Services\PayService\PayService;

class PaymentController extends Controller
{
    public function pay($servicesId)
    {
        return (new PayService())->payment($servicesId);
    }
}
