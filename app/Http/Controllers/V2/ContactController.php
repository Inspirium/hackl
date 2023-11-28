<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Notifications\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function submit(Request $request) {
        Mail::to('info@tenis.plus')->send(new \App\Mail\ContactMessage($request));
    }
}
