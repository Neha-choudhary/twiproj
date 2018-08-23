<?php
require './twitter/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;


$oauth_access_token = "531047460-00K62ZnUkdNfxota5fx9bZb28mIw3aaGFJR9cgnU";
$oauth_access_token_secret = "z362YlnJFwIIAZsdMhKxVvznZlDjKRGxDhTN5rhJtZp7i";
$consumer_key = "VYjyS0kyIAQtO9wMmH9Xcc73k";
$consumer_secret = "5fEwBA8OO3mjSkKhbeTfu6W4tXY81gGaO8EBUi5FWi7xGEGX92";


$connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret);	
/*
$profiles = array();
//$sc_name = 'LarryWentz';
$sc_name ='SrBachchan';

$cursor = -1;
while ($cursor != 0) {
    $ids = $connection->get("friends/ids", array("screen_name" => $sc_name, "cursor" => $cursor));
    $cursor = $ids->next_cursor;
    $ids_arrays = array_chunk($ids->ids, 100);
    foreach($ids_arrays as $implode) {
        $user_ids=implode(',', $implode);
        $results = $connection->get("users/lookup.json", array("user_id" => $user_ids));
        foreach($results as $profile) {
          $profiles[$profile->name] = $profile;
        }
    }
}

file_put_contents('users.txt', $out);
*/

/*
ini_set('max_execution_time', 600);
ini_set('memory_limit', '1024M');
//require_once('twitteroauth.php');
$cursor = -1; // first page
$profiles = array();
    while( $cursor != 0 ){
        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret);

        $sc_name = 'SrBachchan';
        $cursor = "&cursor=" + $cursor; 
        $ids = $connection->get("https://api.twitter.com/1.1/friends/ids.json?screen_name=$sc_name".$cursor);
        $cursor = $ids->next_cursor;
        if(!is_array($ids->ids)) break;
        $ids_arrays = array_chunk($ids->ids, 100);
        $i=1;
        foreach($ids_arrays as $implode) {
            $user_ids=implode(',', $implode);
            $results = $connection->get("https://api.twitter.com/1.1/users/lookup.json?user_id=$user_ids");
            foreach($results as $profile) {
                $profiles[$profile->name] = $profile;
            }
        }
    }
    foreach($profiles as $profile) 
    {
    echo $i. "-" .$profile->name . "<br />";
    $i++;
    }
    /*/

    // $tweet = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret);

/*
    $tweet = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret);

$out="";
$cursor = "-1";

//
$followers=$tweet->get("followers/list", ["screen_name"=>'Niraj_visana']);
		$followers=json_encode($followers);
		$t=json_decode($followers,true);
//

//$t = json_decode($tweet->get('followers/list', array('screen_name' => 'Niraj_visana', 'count' => 200)), true);
if (isset($t['errors'])) {
	echo "ERRORS !!!!! ";
	print_r($t['errors']);
	die();
}
$cursor = $t['next_cursor_str'];
while ($cursor != 0) {
	foreach ($t['users'] as $user) {
	    //$tweet->post('direct_messages/new', array('screen_name' => $user['screen_name'], 'text' => 'Hello!'));
	    $out = $out.$user['screen_name']."<br>";
	}
	$out = $out."NEXTPART";
	sleep(1);	
	$t = json_decode($tweet->get('followers/list', array('screen_name' => 'Niraj_visana', 'cursor' => $cursor, 'count' => 200)),true);
	$cursor = $t['next_cursor_str'];
}
echo $out;
// save to file



*/

if(isset($_SESSION['access_token']))
{
    $access_token = $_SESSION['access_token'];
    //$connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET,$access_token['oauth_token'],$access_token['oauth_token_secret']);
    $follower=$connection->get('followers/list',["count"=>200]);
    $follower_name = array();
    if(isset($follower->users))
    {
        foreach ($follower->users as $f) {
            array_push($follower_name, ["name"=>$f->name,"screen_name"=>$f->screen_name,"profile"=>$f->profile_image_url_https]);
        }
    }
    echo $follower_name = json_encode($follower_name);
}
else
{
    echo "not set session";
}

file_put_contents('users.txt', $follower_name);
?>