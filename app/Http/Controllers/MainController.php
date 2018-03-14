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

        if ($request->expectsJson()) {
            $res = ['error' => [
                'message' => 'You need to authorize this app to access Facebook on your behalf, get an access token using the link below in your browser.',
                'link' => $helper->getLoginUrl(env('APP_URL').'/login-callback'),
                ]
            ];
            return response()->json($res);
        }
        else {
            if (isset($_SESSION['facebook_access_token'])) {
                $logoutUrl = $helper->getLogoutUrl($_SESSION['facebook_access_token'], env('APP_URL'));
                return view('home')->with(['logoutUrl' => $logoutUrl]);
            } else {
                $loginUrl = $helper->getLoginUrl(env('APP_URL').'/login-callback');
                return view('home')->with(['loginUrl' => $loginUrl]);
            }
        }
    }

    public function profile(Request $request, $id)
    {
        if ($request->headers->has('authorization')) {
            $accessToken = explode(' ', $request->header('authorization'))[1];
        } else if (isset($_SESSION['facebook_access_token'])) {
            $accessToken = $_SESSION['facebook_access_token'];
        } else {
            return redirect('/');
        }

        try {
            $response = $this->fb->get('/'.$id.'?fields=first_name,last_name', $accessToken);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            $res = ['error' => ['message' => $e->getMessage()]];
            return response($res, '422');
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            $res = ['error' => ['message' => $e->getMessage()]];
            return response($res, '422');
        }

        $node = $response->getGraphNode()->asArray();
        
        return response()->json($node);
    }

    public function logout()
    {
        unset($_SESSION['facebook_access_token']);
        return redirect('/');
    }

    public function notFound()
    {
        $res = ['error' => [
                'message' => 'Endpoint not found',
                ]
            ];
        return response()->json($res, '404');
    }
}
