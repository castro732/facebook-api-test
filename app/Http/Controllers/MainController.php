<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class MainController extends Controller
{
    protected $fb;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Start the session
        if(!session_id()) {
            session_start();
        }

        $this->fb = new \Facebook\Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => 'v2.12',
        ]);
    }

}
