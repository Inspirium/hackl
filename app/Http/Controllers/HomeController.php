<?php

namespace App\Http\Controllers;

use App\Notifications\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('www.home');
    }

    public function contact()
    {
        return view('www.contact');
    }

    public function postContact(Request $request)
    {
        Notification::route('mail', 'marko@inspirium.hr')->notify(new ContactMessage($request));

        return view('www.contact');
    }

    public function prices()
    {
        return view('www.prices');
    }

    public function terms()
    {
        return view('www.terms');
    }
}
