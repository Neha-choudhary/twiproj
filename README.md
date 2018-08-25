# Twitter-Timeline Challenge

This twitter challenge includes use of twitter api for fetching details like user's home Timeline, user's 10 followers list and their tweets, A download section to download Any twitter user's followers list, you will get followers data through Mail, Due to twitter rate limit Directly it is not posiible to download Followers data.

## Getting Started

First thing you need a developer Account to use twitter API(update july 2018)
so If you don't have one:

* [Twitter dev](https://developer.twitter.com/content/developer-twitter/en.html) - Apply here

It will take up to two weeks or more (For me duration was 12 days) ,
then only they permit you to Create App.


### Prerequisites

* Twitter developer Account And create a App 
* A decent server to host your site(Because twitter callback url not supports localhost, if you still want to do it then change your host file from system drive)
* Read about background jobs in linux or php
 

### Installing

Copy Your API keys and other parameters:

```
path config/config.php
```

And set your cronjob path:(Path is Different For some servers check with your hosting service, current demonstration of GoDaddy Hosting service)


My original Script not Supports on GoDaddy but Here you can check Output Of it

<link>

## Running the tests

For automated testing of the system, I used **scrutinizer-ci**  To maintain standards 155 patches has been done.

Manually i didn't get Time to Test system properly , So Feel Free to raise Any issue.


## Deployment

You can deploy it easily on any server Things to Keep in mind:
* Check your hosting service Cronjob path and supports
* Check your hosting services Mail or phpmailer Host And ports

 **For Example** 

GoDaddy have some Strict Rules Related Phpmailer or mail function:

 ```
    $mail->IsMail();
    $mail->Host = 'relay-hosting.secureserver.net';
    $mail->Port = 25;
    $mail->SMTPAuth = false;
    $mail->SMTPSecure = false;
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->IsHTML(true);
```
* Hope this could save someone's One or Two hour.
* Try **Testmail.php** To send a test mail.

## Built With

* PHP 7
* JS & jquery-1.9.1
* css
* Ajax
* some shell part

## Contributing

Feel Free to Raise Any Issue , Feel Free to Use Code , Also let me know If you created Something Cool From This code, i will be glad to now , Mail me at :

niraj.visana@gmail.com


## Versioning

This is First Version so **Update to be done And Future Fixes Includes:**

* I will properly write script to download followers, this one is temporary script Original Script is not supports on GoDaddy, i will update it later.

* I've written Code to download followers list in xml,json,excel but i commented that because it's not tested yet.

* Do not put '@' in Textbox while Entering Handler And code will verify your inputed handler so if entered handler is invalid , it will not gonna start a job.


## Dev:

* **Niraj visana** - [nirajspitze9](https://github.com/nirajspitze9)
* **Email** - niraj.visana@gmail.com 

## Acknowledgments

* Many thanks to **StackOverFlow**

* There are so many Youtube toutorials are there i refered this given playlist:
(check it out and do support creator)
https://www.youtube.com/watch?v=9Q5SW5VUVoM&list=PLDjrWL5SkWwDfTIIxfEKpYRwMBASnnbPa

* Twitter Api library used :  https://twitteroauth.com by Abraham.

* And so many gist codes.

