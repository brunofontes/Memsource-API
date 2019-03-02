<?php
session_start();
$authorize_url = 'https://cloud.memsource.com/web/oauth/authorize';
$token_url = 'https://cloud.memsource.com/web/oauth/token';

$config = parse_ini_file('config.ini');

//	callback URL specified when the application was defined--has to match what the application says
$callback_uri = $config['callbackUri'];

//	client (application) credentials - located at apim.byu.edu
$client_id = $config['id'];
$client_secret = $config['secret'];

if (isset($_GET['code'])) {
    //	what to do if there's an authorization code
    $access_token = getAccessToken($_GET['code']);
    $_SESSION['token'] = $access_token;
    header('Location: /');
} elseif (isset($_GET['error'])) {
    echo 'Access Denied';
} else {
    //	what to do if there's no authorization code
    getAuthorizationCode();
}

//	step A - simulate a request from a browser on the authorize_url
//		will return an authorization code after the user is prompted for credentials
function getAuthorizationCode()
{
    global $authorize_url, $client_id, $callback_uri;

    $authorization_redirect_url = $authorize_url . '?response_type=code&client_id=' . $client_id . '&redirect_uri=' . $callback_uri . '&scope=openid';

    header('Location: ' . $authorization_redirect_url);

    //	if you don't want to redirect
    // echo "Go <a href='$authorization_redirect_url'>here</a>, copy the code, and paste it into the box below.<br /><form action=" . $_SERVER["PHP_SELF"] . " method = 'post'><input type='text' name='authorization_code' /><br /><input type='submit'></form>";
}

//	step I, J - turn the authorization code into an access token, etc.
function getAccessToken($authorization_code)
{
    global $token_url, $client_id, $client_secret, $callback_uri;

    $authorization = base64_encode("$client_id:$client_secret");
    $header = ["Authorization: Basic {$authorization}", 'Content-Type: application/x-www-form-urlencoded'];
    $content = "grant_type=authorization_code&code=$authorization_code&redirect_uri=$callback_uri";

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $token_url,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $content
    ]);
    $response = curl_exec($curl);
    $objectResponse = json_decode($response);
    curl_close($curl);

    if ($response === false) {
        echo 'Failed';
        echo curl_error($curl);
        echo 'Failed';
    } elseif (isset($objectResponse->error)) {
        echo 'Error:<br />';
        echo $authorization_code;
        echo $response;
    }

    return $objectResponse->access_token;
}
