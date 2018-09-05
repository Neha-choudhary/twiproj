<?php

// for error reporting
ini_set('display_errors', 1); 
error_reporting(E_ALL);

//memory limit set to necessary
ini_set('memory_limit', '-1');

//twitter library n namespace
require '/home/bme2kggy0iwu/public_html/twiproj/twitter/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

//controller writes to cron.txt file cronjob to perform
require_once("controller.php");

//dompdf to create pdf file
include '/home/bme2kggy0iwu/public_html/twiproj/library/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

//uncomment this values while running through cron job

$path = $argv[0];
$format = $argv[1];
$int_cursor = $argv[2];
$screen_name = $argv[3];
$email = $argv[4];

//static values

// $path = '/home/bme2kggy0iwu/public_html/twiproj/flwdwn.php';
// $format='csv';
// $int_cursor =-1;
// $screen_name='khuntimd';
// $email = 'niraj.visana@gmail.com';



shell_exec("echo '$argv[0] $argv[1] $argv[2] $argv[3] $argv[4]' >> /home/bme2kggy0iwu/public_html/twiproj/argv.txt");
    //shell_exec("sudo touch /var/www/html/rtcamp/tmp");


define("ID", "FollowerName");
define("VALUE", "FollowerScreenName");
define("FILE_NAME", $screen_name);
define("ROOT", "Follower");
$cursor = $int_cursor;


if (!isset($_SESSION['access_token']))
{
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $request_token = $connection->oauth('oauth/request_token', array('oauth_callback'=>OAUTH_CALLBACK));
    $_SESSION['oauth_token'] = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
    $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
}
else
{
    $accesstoken = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $accesstoken['oauth_token'], $accesstoken['oauth_token_secret']);
}

if ($cursor != 0)
{

    // $flwdwn=$connection->get('followers/list',["screen_name"=>$_SESSION['flwdwn'],"count"=>200,"cursor"=>$cursor]);
    $td_t = array();
    for ($i = 1; $cursor != 0; $i++) { 
        $flwdwn = $connection->get('followers/list', ["screen_name"=>$screen_name, "count"=>200, "cursor"=>$cursor]);
        
        if (!isset($flwdwn->users))
        {
            shell_exec("echo 'inside first if' >> /home/bme2kggy0iwu/public_html/twiproj/argv.txt");
            $reading = fopen('/home/bme2kggy0iwu/public_html/twiproj/cron.txt', 'r');
            $writing = fopen('/home/bme2kggy0iwu/public_html/twiproj/cron.tmp', 'w');
         
            $replaced = false;
            
            while (!feof($reading)) {
            $line = fgets($reading);
            if (stristr($line, "*/15 * * * * /usr/local/bin/php /home/bme2kggy0iwu/public_html/twiproj/flwdwn.php $format $int_cursor $screen_name $email")) {
                $line = "*/15 * * * * /usr/local/bin/php /home/bme2kggy0iwu/public_html/twiproj/flwdwn.php $format $cursor $screen_name $email \n";
                $replaced = true;
            }
            fputs($writing, $line);
            }

            fclose($reading); 
            fclose($writing);
            
            // might as well not overwrite the file if we didn't replace anything
            if ($replaced) 
            {
            rename(__DIR__.'/cron.tmp', __DIR__.'/cron.txt');
            } else {
            unlink(__DIR__.'/cron.tmp');
            }

            shell_exec("chmod 777 ".__DIR__.'/cron.txt');
            
            //$cmd = "bash ".__DIR__."/cron.sh";
            //shell_exec($cmd);

            echo shell_exec('sh '.__DIR__.'/cron.sh');

            break;
        }
        foreach ($flwdwn->users as $f) {
            $tmp = new stdClass;
            $tmp->id_str = $f->name;
            $tmp->text = $f->screen_name;
            array_push($td_t, [$tmp]);
        }
        $cursor = $flwdwn->next_cursor;
    }

    
    if ($format == "pdf")
    {

        // $file_name = "followers details -".$screen_name.".pdf";                         
                                
                
                    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
                    $html .= '<html xmlns="http://www.w3.org/1999/xhtml">';
                    $html .= '<head>';
                    $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
                    $html .= '<title>Twitter Followers</title>';
                    $html .= '<style>* { font-family: "DejaVu Sans","ariblk", "monospace","Times-Roman"; } @page { margin: 0em; } </style>';
                    $html .= '</head>';
                    $html .= '<body bgcolor="#ffffff">';
                    $html .= '<table id="Table_01" width="100%" border="1" cellpadding="0" cellspacing="0"  bgcolor="#ffffff">';
                    $html .= '<tr>';
                    $html .= '<td colspan="4" style="text-align:center; padding:10px; font-weight:bold; font-size:16px;">followers list<br/></td>';
                    $html .= '</tr>';
                    $html .= '<tr>';
                    $html .= '<td style="padding:5px; text-align:left; font-weight:bold;">No</td><td style="padding:5px; text-align:left; font-weight:bold;">User Name</td><td style="padding:5px; text-align:left; font-weight:bold;">Screen Name</td>';
                    $html .= '</tr>';
                    $i = 0;
                    foreach ($td_t as $rows) {
                        foreach ($rows as $row) {
                        $i = $i + 1;
                        $html .= '<tr>';
                        $html .= '<td style="padding:5px; text-align:left; width:18%;">'.trim($i).'</td>';
                        $html .= '<td style="padding:5px; text-align:left; width:18%;">'.trim($row->id_str).'</td>';
                        $html .= '<td style="padding:5px; text-align:left; width:18%;">'.trim($row->text).'</td>';
                        $html .= '</tr>';
                        }
                    }
                    
                    $html .= '</table>';
                    $html .= '</body>';
                    $html .= '</html>';
                    
                    // $dompdf = new DOMPDF();
                    // $dompdf->load_html($html);
                    // $dompdf->set_paper('a4','portrait');
                    // $dompdf->render();
                    // $dompdf->stream($file_name);

                    $dompdf = new Dompdf();
                    $dompdf->loadHtml($html);

                    $dompdf->setPaper('A4', 'landscape');

                    // Render the HTML as PDF
                    $dompdf->render();

                    // Output the generated PDF to Browser
                    //$dompdf->stream('testpdf',array('Attachment'=>0));

                    
                    $output = $dompdf->output();
                    $file_to_save = __DIR__."/".$screen_name.'.'.$format;
                    file_put_contents($file_to_save, $output);


        
    } else
    {
        $file = fopen(__DIR__."/".FILE_NAME.'.'.$format, 'a');
        
        if ($int_cursor == -1)
        {
            fputcsv($file, array(ID, VALUE));
        }

        foreach ($td_t as $rows)
        {
            foreach ($rows as $row) {
                fputcsv($file, array($row->id_str, $row->text));
            }
        }

        fclose($file);
        
    }
}

if ($cursor == 0)
{

    require '/home/bme2kggy0iwu/public_html/twiproj/PHPMailer-master/src/Exception.php';
    require '/home/bme2kggy0iwu/public_html/twiproj/PHPMailer-master/src/PHPMailer.php';
    require '/home/bme2kggy0iwu/public_html/twiproj/PHPMailer-master/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    $mail->IsMail();
    $mail->Host = 'relay-hosting.secureserver.net';
    $mail->Port = 25;
    $mail->SMTPAuth = false;
    $mail->SMTPSecure = false;
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->IsHTML(true);
    $mail->Username = "twiproj@visana.xyz";  
    $mail->Password = "Nir@jNNN9";
    $mail->SetFrom('twiproj@visana.xyz', 'Twitter-data');
    $mail->Subject = "Followers Data";
    $mail->AltBody = "";
    $mail->AddAddress($email);
    $mail->MsgHTML("Your requested follower data is in file attached below");
    
    if ($format == "pdf")
    {
        $mail->AddAttachment($file_to_save);    
    } else
    {
        $mail->AddAttachment(__DIR__."/".FILE_NAME.'.'.$format);
    }
    
    

        // if(!$mail->Send()) {
        //    echo "Mailer Error: " . $mail->ErrorInfo;
        //    shell_exec("echo 'failed to send mail' >> /home/bme2kggy0iwu/public_html/twiproj/argv.txt");
        //  //  $output = exec('crontab -r');
        // } else {
        //    shell_exec("echo 'sent mail' >> /home/bme2kggy0iwu/public_html/twiproj/argv.txt");
        //    echo "Message has been sent";
        //  //  $output = exec('crontab -r');
        // } 



        // $DELETE = stristr($line,"*/15 * * * * php $path $format $int_cursor $screen_name $email ");

        //     $data = file("__DIR__.'/cron.txt'");

        //     $out = array();

        //     foreach($data as $line) {
        //         if(trim($line) != $DELETE) {
        //             $out[] = $line;
        //         }
        //     }

        //     $fp = fopen("__DIR__.'/cron.txt'", "w+");
        //     flock($fp, LOCK_EX);
        //     foreach($out as $line) {
        //         fwrite($fp, $line);
        //     }
        //     flock($fp, LOCK_UN);
        //     fclose($fp);  

       
    if ($mail->Send()) {
        $reading = fopen('/home/bme2kggy0iwu/public_html/twiproj/cron.txt', 'r');
        $writing = fopen('/home/bme2kggy0iwu/public_html/twiproj/cron.tmp', 'w');
        $str = "*/15 * * * * /usr/local/bin/php /home/bme2kggy0iwu/public_html/twiproj/flwdwn.php ".$format." -1 ".$screen_name." ".$email." \n";
        $replaced = false;
        
        while (!feof($reading)) {
        $line = fgets($reading);

        if (stristr($line, "*/15 * * * * /usr/local/bin/php /home/bme2kggy0iwu/public_html/twiproj/flwdwn.php $format $int_cursor $screen_name $email")) {
            shell_exec("echo 'inside if' >> /home/bme2kggy0iwu/public_html/twiproj/argv.txt");
            $line = "";
            $replaced = true;
        }
        shell_exec("echo 'line  '.$line >> /home/bme2kggy0iwu/public_html/twiproj/argv.txt ");
        shell_exec("echo '  replaced  '.$replaced >> /home/bme2kggy0iwu/public_html/twiproj/argv.txt ");
        shell_exec("echo $str >> /home/bme2kggy0iwu/public_html/twiproj/argv.txt ");



        fputs($writing, $line);
        }
        fclose($reading);
        fclose($writing);
        

        // might as well not overwrite the file if we didn't replace anything
        if ($replaced) 
        {
        rename(__DIR__.'/cron.tmp', __DIR__.'/cron.txt');
        } else
        {
        unlink(__DIR__.'/cron.tmp');
        }

        
        //shell_exec("chmod 777 ".__DIR__.'/cron.txt');
        echo shell_exec('sh '.__DIR__.'/cron.sh');
        unlink(__DIR__."/".FILE_NAME.'.'.$format);
    }
        
    else {
        echo "Error while sending mail : ".$mail->ErrorInfo;
    }


}
