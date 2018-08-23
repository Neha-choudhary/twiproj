<?php
ini_set("display_errors", 1);
session_start();
//require "autoload.php";
//include_once "googleloginfunc.php";
include 'common.inc.php';
require './twitter/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;


    $user = $flwdwn = null;

// if(isset($_REQUEST['btnlogout']))
// {
// 	$user=null;
// 	$tweets=null;
// 	$connection=null;
// 	$request_token=null;
// 	session_destroy();
// 	$url=null;
// }

// if(!isset($_SESSION['access_token']))
// {
// 	$connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET);
// 	$request_token = $connection->oauth('oauth/request_token',array('oauth_callback'=>OAUTH_CALLBACK));
// 	$_SESSION['oauth_token'] = $request_token['oauth_token'];
// 	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
// 	$url=$connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token'] ));
// }
// if($user=="" && isset($_SESSION['access_token']))
// {

// 	$access_token = $_SESSION['access_token'];
// 	$connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET,$access_token['oauth_token'],$access_token['oauth_token_secret']);
// 	$user = $connection->get("account/verify_credentials");

// 	$tweets=$connection->get("statuses/user_timeline",['count'=>10]);

// 	$follower=$connection->get('followers/list',["count"=>200]);
// 	$follower_name = array();
// 	if(isset($follower->users))
// 	{
// 		foreach ($follower->users as $f) {
// 			array_push($follower_name, ["label"=>$f->name,"value"=>$f->screen_name,"img"=>$f->profile_image_url_https]);
// 		}
// 	}

// 	$follower_name = json_encode($follower_name);
// }

if(isset($_REQUEST['flwdwn']))
{
    $token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token['oauth_token'], $token['oauth_token_secret']);
    $flwdwn=$connection->get('users/lookup',["screen_name"=>$_REQUEST['flwdwn']]);

    if(isset($flwdwn->errors))
    {
        echo "No User Found";
    }
    else
    {
        echo "Success";
        $_SESSION['flwdwn']=$flwdwn[0]->screen_name;
    }
}

if(isset($_REQUEST['format']))
{
    $file = fopen("cron.txt","a");
    $email = $_REQUEST['email'];
    $format = $_REQUEST['format'];
    $str = "*/15 * * * * /usr/local/bin/php ".getcwd()."/flwdwn.php ".$format." -1 ".$_SESSION['flwdwn']." ".$email." \n";

    //$str = "*/15 * * * * /usr/local/bin/php7.6 ".getcwd()."/flwdwn.php ".$format." -1 ".$_SESSION['flwdwn']." ".$email." \n";

    $result = fwrite($file,$str);
    if($result == true)
    {
        //$cmd = "sudo bash".getcwd()."/cron.sh";
            //@exec($cmd);
        //$cmd = ".".getcwd()."/cron.sh > ./home/bme2kggy0iwu/public_html/twiproj/log.txt";

        //$out=shell_exec("./home/bme2kggy0iwu/public_html/twiproj/cron.sh > ./home/bme2kggy0iwu/public_html/twiproj/log.txt");
        echo shell_exec('sh ./cron.sh');

            $output = exec('crontab -l');
            echo "cron Running output ==> ".$output;
    }
}

//}

?>	