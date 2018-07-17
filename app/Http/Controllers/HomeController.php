<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Display the homepage
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function home(Request $request)
    {
        if(!$this->isBotRequest()) {
            $this->init($request);
        }
        return view('home', ['user' => $this->user]);
    }

    /**
     * displays a loading iframe
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function loading(Request $request)
    {
        return view('loading', []);
    }
}
