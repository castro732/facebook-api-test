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

    public function home(Request $request)
    {
        $helper = $this->fb->getRedirectLoginHelper();

        if (isset($_SESSION['facebook_access_token'])) {
            $logoutUrl = $helper->getLogoutUrl($_SESSION['facebook_access_token'], 'http://localhost:8008/');
            return view('home')->with(['logoutUrl' => $logoutUrl]);
        } else {
            $loginUrl = $helper->getLoginUrl('http://localhost:8008/login-callback');
            return view('home')->with(['loginUrl' => $loginUrl]);
        }
    }
}
