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

    public function callback(Request $request)
    {
        $helper = $this->fb->getRedirectLoginHelper();
        // CSRF state param
        if (isset($_GET['state'])) {
            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
        }
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $res = ['error' => ['message' => $e->getMessage()]];
            return response($res, '422');
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $res = ['error' => ['message' => $e->getMessage()]];
                return response($res, '422');
        }

        if (isset($accessToken)) {
            // Logged in!
            $_SESSION['facebook_access_token'] = (string) $accessToken;
            return redirect('/');
        } elseif ($helper->getError()) {
            // The user denied the request
            $res = ['error' => ['message' => 'user denied the request']];
            return response($res, '422');
        }
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

    public function profile(Request $request, $id)
    {
        if (isset($_SESSION['facebook_access_token'])) {
            try {
                $response = $this->fb->get('/'.$id.'?fields=first_name,last_name', $_SESSION['facebook_access_token']);
            } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                $res = ['error' => ['message' => $e->getMessage()]];
                return response($res, '422');
            } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                $res = ['error' => ['message' => $e->getMessage()]];
                return response($res, '422');
            }
        } else {
            return redirect('/');
        }

        $node = $response->getGraphNode();

        return $node;
    }

    public function logout()
    {
        unset($_SESSION['facebook_access_token']);
        return redirect('/');
    }
}
