<?php

session_start();


//function to show error on page

ini_set('display_errors',1); 
error_reporting(E_ALL);

//include And namespace part

//include "./config/config.php";
require './twitter/twitteroauth/autoload.php';
include 'common.inc.php';
use Abraham\TwitterOAuth\TwitterOAuth;

$today = time();

//include And namespace part end


if (!isset($_SESSION['access_token'])) {
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET); 	//establishing connection

	//obtaining request token
	$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

	//above code is storing data into sessions

	$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
	// above is the authentication url
	echo "<a href='$url'><img src='twitter-login-blue.png' style='margin-left:4%; margin-top: 4%'></a>";
} 
else{

	$access_token = $_SESSION['access_token'];

	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);	

	$login_user_details = $connection->get('account/verify_credentials');	//user details if we need any

		//print_r ($login_user_details);
		
			//getting user's data and storing them in variables	

			$login_user_id = $login_user_details->id;
			$login_user_name = $login_user_details->name;
			$login_user_screen_name = $login_user_details->screen_name;
			$login_user_location = $login_user_details->location;
			$login_user_friends_count = $login_user_details->friends_count;
			$login_user_profile_image_url = $login_user_details->profile_image_url;
			$login_user_profile_bgcolor = $login_user_details->profile_background_color;
			$login_user_profile_bgimage_url = $login_user_details->profile_background_image_url;
			


			//fetching tweets
			$login_user_homeline_tweets = $connection->get('statuses/home_timeline', array('screen_name' => $_SESSION['access_token']['screen_name'],'count' => 10,'include_rts' => true,'include_entities' => true));


			//fetching followers details, scrren name and ids and storing them in array,change No_OF_FOLLOWERS constant in config file to change count


			$login_user_all_follower_lists = $connection->get('followers/ids');
			$login_user_all_follower_ids = $login_user_all_follower_lists->ids;

			if(count($login_user_all_follower_ids) >= 10){
				$random_keys = array_rand($login_user_all_follower_ids, NO_OF_FOLLOWERS);
				foreach($random_keys as $key){
					$login_user_follower_ids[] = $login_user_all_follower_ids[$key];								
				}
				

				
				if(isset($login_user_follower_ids) && count($login_user_follower_ids) > 0){
					foreach($login_user_follower_ids as $follower_ids){
						$friend_realtion_details = array();
						$friend_realtion_details = $connection->get('friendships/show', array('target_id' => $follower_ids));

						
						if(isset($friend_realtion_details->relationship->target->screen_name)){
							$friend_screen_name_details[] = array('id'=>$follower_ids, 'screen_name'=>$friend_realtion_details->relationship->target->screen_name);
							
							$friend_name_details[] = array('name'=>$follower_ids, 'screen_name'=>$friend_realtion_details->relationship->target->screen_name);

						}
					}
				}

				
				if(isset($friend_screen_name_details) && count($friend_screen_name_details) > 0){
					foreach($friend_screen_name_details as $friend_screen_name){						
						$friend_details[] = $connection->get('users/show', array('user_id' => $friend_screen_name['id'],'screen_name' => $friend_screen_name['screen_name']));
						 	
					}
				}
			}


				///////Following


			$login_user_all_following_lists = $connection->get('friends/ids');
			$login_user_all_following_lists = $login_user_all_following_lists->ids;

			if(count($login_user_all_following_lists) >= 10){
				$random_keys = array_rand($login_user_all_following_lists, NO_OF_FOLLOWERS);
				foreach($random_keys as $key){
					$login_user_following_ids[] = $login_user_all_following_lists[$key];								
				}
			}



				
				if(isset($login_user_following_ids) && count($login_user_following_ids) > 0){
					foreach($login_user_following_ids as $following_ids){
						$friend_realtion_following_details = array();
						$friend_realtion_following_details = $connection->get('friendships/show', array('target_id' => $following_ids));


						
						if(isset($friend_realtion_following_details->relationship->target->screen_name)){
							$following_screen_name_details[] = array('id'=>$following_ids, 'screen_name'=>$friend_realtion_following_details->relationship->target->screen_name);

							
							$friend_name_details[] = array('name'=>$following_ids, 'screen_name'=>$friend_realtion_following_details->relationship->target->screen_name);

						}
					}
				}
				
				
				if(isset($following_screen_name_details) && count($following_screen_name_details) > 0){
					foreach($following_screen_name_details as $following_screen_name){						
						$following_details[] = $connection->get('users/show', array('user_id' => $following_screen_name['id'],'screen_name' => $following_screen_name['screen_name']));
						 	
					}
				}


				
				////get followers 

	$follower=$connection->get('followers/list',["count"=>200]);
	$follower_name = array();
	if(isset($follower->users))
	{
		foreach ($follower->users as $f) {
			array_push($follower_name, ["name"=>$f->name,"screen_name"=>$f->screen_name,"profile"=>$f->profile_image_url_https]);
		}
	}
	$follower_name = json_encode($follower_name);

		
?>
	

<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo SITE_NAME; ?></title>

        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="stylesheet" type="text/css" href="./css/style.css" />
        <script type="text/javascript" src="./js/jquery-1.8.1.min.js"></script>

        <script src="./js/jquery-1.9.1.js"></script>
       
       	<meta name="viewport" content="width=device-width, initial-scale=1">
       
		<link rel="stylesheet" type="text/css" href="./css/jquery.bxslider.css" />
        <script type="text/javascript" src="./js/jquery.bxslider.js"></script>
  		 <script type="text/javascript">
  
 	 	var mainSlider;


 	 	$(document).ready(function(){

			  $('#txtflwdwn').blur(function(event) {
			    
			    
			    	$.ajax({
						url:"controller.php?flwdwn="+$(this).val(),
						type:"post",
						success:function(response){
							if(response == "Success")
							{
								$("#flwdwnbtn").removeClass("disabled");
							}
							else
							{
								$("#flwdwnbtn").addClass("disabled");
								alert("Not a valid screen name");

							}
						},
						failure:function(response){
							console.log(response);
						}
					});
			

			  });

		});
		
		$(document).ready(function(){		  
		    mainSlider = $('.slider4').bxSlider({
				slideWidth: 300,
				minSlides: 2,
				maxSlides: 3,
				moveSlides: 1,
				slideMargin: 10,
				auto: true
			  });
		});

		function tweetDownload(){
			if($.tirm($('select[name="download_format"]').val()) != ''){
				return true;
			}else{
				return false;
			}
		}

		function tmp(format){
			

		var person = prompt("All the Follower Data will be mailed on the given Email Address","Enter Email Address");
				if (person != "") {
					$.ajax({
						url:"controller.php?format="+format+"&email="+person,
						type:"post",
						success:function(response){
							console.log();
						},
						failure:function(response){
							console.log(response);
						}
					});			
				}
			}
		
			


		function getUserTweet(screen_name){
			if(screen_name != ''){
				$.ajax({
					url: "ajax-php/ajax-get-user-tweets.php",
					type: "POST",
					data: "screen_name="+screen_name,
					dataType: "json",
					async:false,
					success: function(resp){				
						 if(resp.ErrorCode == 0){
						 	$('#slider_tweet_content').html('Loading...');
							$('#slider_tweet_content').html(resp.Content);
							mainSlider.destroySlider();
							mainSlider = $('.slider4').bxSlider({
								slideWidth: 300,
								minSlides: 2,
								maxSlides: 3,
								moveSlides: 1,
								slideMargin: 10,
								auto: true
							});							
						 }
					}
				});
			}
			return false;
		}

	</script>
</head>
<body>
	
	<div id="bodybg"></div>


        <div class="wrapper">
            <div style="top: 0px; left: 0px; width: 100%; height: 100%;">
                <div style="height:auto; width:900px; margin: 50px auto;">
                	<h1 style="color:#333333;">&#126; <?php echo SITE_NAME; ?> &#126;</h1>
        			
                    <div class="header-content">
                    	<div class="header-left-content">
                        &nbsp;
                        </div>
                        <div class="header-right-content-top">
                            <div class="header-right-content-top-left-panel">
                                <img src=<?php if(isset($login_user_profile_image_url)){ echo $login_user_profile_image_url; } ?> />
                            </div>
                            <div class="header-right-content-top-right-panel">
                                <div class="header-right-content-top-right-panel-top">
                                	<?php if(isset($login_user_name)){ echo $login_user_name; } ?>
                                </div>
                                <div class="header-right-content-top-right-panel-bottom-left">
                                	@<?php if(isset($login_user_screen_name)){ echo $login_user_screen_name; } ?>
                                </div>
                                <div class="header-right-content-top-right-panel-bottom-right">
                                	<a href="<?php echo ROOT_PATH.'signout.php'; ?>">Sign Out</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="header-content">
                    	<div class="header-left-content">
                        Hi! <?php if(isset($login_user_name)){ echo $login_user_name; } ?>
                        </div>
                        <form method="post" action="<?php echo ROOT_PATH; ?>download.php" target="_blank" onSubmit="return tweetDownload();">
                        <input type="hidden" name="login_user_screen_name" value="<?php if(isset($login_user_screen_name)){ echo $login_user_screen_name; } ?>" />
                        <div class="header-right-content">
                        Your Tweets 
                        <select name="download_format">
                        	<option value="">--- Select Format ---</option>
                            <option value="csv">CSV</option>
                            <option value="json">JSON</option>
                            <option value="pdf">PDF</option>
                            <option value="xls">XLS</option>
                            <option value="xml">XML</option>                            
                        </select>
                        <input type="submit" name="download_tweet" value="Download" class="download_btn" />
                        </div>
                        </form>
                    </div>
                    <br clear="all" />
                    <?php
					if(count($login_user_homeline_tweets) > 0){						
					?>
                    <div class="slider4" id="slider_tweet_content">
                    	<?php						
						foreach($login_user_homeline_tweets as $homeline_tweet){
							$timeDiff = $func->dateDiff($today, $homeline_tweet->created_at, 1);
							$tweet_text = $homeline_tweet->text;
							# Turn URLs into links
							$tweet_text = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\./-]*(\?\S+)?)?)?)@', '<a target="blank" title="$1" href="$1">$1</a>', $tweet_text);
				
							#Turn hashtags into links
							 $tweet_text = preg_replace('/#([0-9a-zA-Z_-]+)/', "<a target='blank' title='$1' href=\"http://twitter.com/search?q=%23$1\">#$1</a>",  $tweet_text);
				
							#Turn @replies into links
							 $tweet_text = preg_replace("/@([0-9a-zA-Z_-]+)/", "<a target='blank' title='$1' href=\"http://twitter.com/$1\">@$1</a>",  $tweet_text);
						?>
                        <div class="slide">
                            <div style="background-color:#CCC; height:140px; padding:5px;">
                                <div style="width:100%; margin:3px 0; height:50px;">
                                    <div style="width:48px; margin:0 3px 0 0; float:left; display:inline;">
                                    <img src="<?php echo $homeline_tweet->user->profile_image_url; ?>" alt=""  style="border:1px solid #000;" />
                                    </div>
                                    <div style="float:left; display:inline;">
                                    <?php echo $homeline_tweet->user->name.' - '.$timeDiff; ?>
                                    </div>
                                </div>
                                <div style="width:100%; margin:3px 0;">
                                <?php
                                echo $tweet_text;
                                ?>
                                </div>
                            </div>
                        </div>
                        <?php
						}
						?>
                    </div>
                    <br clear="all" />
                    <?php
					}
					?>
                    
                    <br clear="all" />
                    
                    <div class="header-content">
                    	<div class="header-left-content">
                        Your Followers:
                        </div>
                        <div class="header-left-content">&nbsp;
                        </div>
                    </div>
                    <?php
					if(isset($friend_details) && count($friend_details) >=10){
					?>
                    <div>
                    	<?php	
						foreach($friend_details as $friend_detail){
						?>
                    	<div style="width:28%; display:inline; float:left; background-color:#EEA61C; color:#000; padding:10px; height:50px; margin:9px; 4px; border:1px solid #000;">
                        	<div style="width:50px; height:50px; float:left; display:inline; margin-right:2px;">
                                <img src="<?php echo $friend_detail->profile_image_url; ?>" style="border:1px solid #000;" />
                            </div>
                            <div style="height:50px; float:left; display:inline; color: #FFF;">
                                <div style="width:100%; height:25px; font-weight:bold; margin-left: 10px;">
                                	<a href="javascript:void(0);" onClick="return getUserTweet('<?php echo $friend_detail->screen_name; ?>');"><?php echo $friend_detail->name; ?></a>
                                </div>
                                <div style="width:100%; height:20px; float:left; display:inline; padding:2px;">
                                	@<?php echo $friend_detail->screen_name; ?>
                                </div>
                            </div>
                        </div>
                        <?php
						}

						?>
                    </div>
                    <br clear="all" />
                    <?php
					}
					else
					{
						echo "you have less then 10 followers";
					}
					?>

					<br>
					<div class="header-content">
                    	<div class="header-left-content">
                        You Follow:
                        </div>
                        <div class="header-left-content">&nbsp;
                        </div>
                    </div>
                    <?php
					if(isset($following_details) && count($following_details) >=10){
					?>
                    <div>
                    	<?php	
						foreach($following_details as $following_detail){
						?>
                    	<div style="width:28%; display:inline; float:left; background-color:#EEA61C; color:#000; padding:10px; height:50px; margin:9px; 4px; border:1px solid #000;">
                        	<div style="width:50px; height:50px; float:left; display:inline; margin-right:2px;">
                                <img src="<?php echo $following_detail->profile_image_url; ?>" style="border:1px solid #000;" />
                            </div>
                            <div style="height:50px; float:left; display:inline; color: #FFF;">
                                <div style="width:100%; height:25px; font-weight:bold; margin-left: 10px;">
                                	<a href="javascript:void(0);" onClick="return getUserTweet('<?php echo $following_detail->screen_name; ?>');"><?php echo $following_detail->name; ?></a>
                                </div>
                                <div style="width:100%; height:20px; float:left; display:inline; padding:2px;">
                                	@<?php echo $following_detail->screen_name; ?>
                                </div>
                            </div>
                        </div>
                        <?php
						}

						?>
                    </div>
                    <br clear="all" />
                    <?php
					}
					else
					{
						echo "you follow less then 10 people";
					}
					?>

					<br>
					<br>
                                      
                   <div class="header-content">
                    	<div class="header-left-content">
                        Download Followers:
                        </div>
                        <div class="header-left-content">&nbsp;
                        </div>
                    </div>



					<form allign:"center">
					  	<div class="header-right-content">
                 <label>Enter user handler:</label>
					  <input id="txtflwdwn" type="text" placeholder="Enter screen name here" autocomplete="off"><p 
                                              style="color:red">Without '@'</p>
					 
				 <label>Select Format:</label>	
                        <select name="download_format" id="flwdwnbtn" onchange="tmp(value);">
                        	<option value="">--- Select Format ---</option>
                            <option value="csv">csv format</option>
                            <option value="json">json format</option>
                            <option value="xls">excel format</option>
                            <option value="xml">XML format</option>                            
                        </select>
                        
                        </div>
                        <br>
                        
				</form>



				</div>
                </div>
            </div>
        </div>
				<br>
					<?php
					}
					?>

</body>
</html>		

           			
