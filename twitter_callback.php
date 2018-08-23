<?php
session_start(); 
require_once 'twitter/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

 
$config = require_once 'config.php';

$oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');

//echo $oauth_verifier;
echo "this is oauth_token==> ".$_SESSION['oauth_token'];
echo "this is oauth_token_secret==> ".$_SESSION['oauth_token_secret']; 

if (empty($oauth_verifier)) {
    echo "test";
    // something's missing, go and login again
    header('Location:twitter_login.php');
}

// connect with application token
$connection = new TwitterOAuth(
    $config['consumer_key'],
    $config['consumer_secret'],
    $_SESSION['oauth_token'],
    $_SESSION['oauth_token_secret']
);
 
// request user token
$token = $connection->oauth(
    'oauth/access_token', [
        'oauth_verifier' => $oauth_verifier
    ]
);

$twitter = new TwitterOAuth(
    $config['consumer_key'],
    $config['consumer_secret'],
    $token['oauth_token'],
    $token['oauth_token_secret']
);

$status = $twitter->post(
    "statuses/update", [
        "status" => "Test api tweet"
    ]
);
 
echo ('Created new status with #' . $status->id . PHP_EOL);


?>