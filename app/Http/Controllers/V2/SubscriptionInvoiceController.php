<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionInvoiceController extends Controller
{

    public function index() {

    }

    public function store(Request $request) {
        // school group
        $request->input('school_group');

        //membership

        // general
    }
}
