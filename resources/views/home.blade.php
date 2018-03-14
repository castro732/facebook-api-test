<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Facebook API Test</title>
        <link href='//fonts.googleapis.com/css?family=Raleway:300' rel='stylesheet' type='text/css'>
        <style>
            body {
                margin: 50px 0 0 0;
                padding: 0;
                width: 100%;
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                text-align: center;
                color: #aaa;
                font-size: 18px;
            }

            h1 {
                color: #719e40;
                letter-spacing: -3px;
                font-family: 'Lato', sans-serif;
                font-size: 100px;
                font-weight: 200;
                margin-bottom: 0;
            }
        </style>
    </head>
    <body>
        @if (!isset($_SESSION['facebook_access_token']))
            <h3>You need to authorize this app to access Facebook on your behalf</h3>

            <a href='<?php echo $loginUrl; ?>'>Login to facebook</a>
        @else
            <h3>This app is authorized to access Facebook on your behalf</h3>

            <a href='/logout'>Logout from the app</a>
        @endif
        
    </body>
</html>
