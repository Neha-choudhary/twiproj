 <?php

    require './PHPMailer-master/src/Exception.php';
    require './PHPMailer-master/src/PHPMailer.php';
    require './PHPMailer-master/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();
  //  $mail->IsSMTP(); // enable SMTP

    $mail->IsMail();
    $mail->Host = 'relay-hosting.secureserver.net';
    $mail->Port = 25;
    $mail->SMTPAuth = false;
    $mail->SMTPSecure = false;
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->IsHTML(true);
<<<<<<< HEAD
    $mail->Username = "Your email";  
    $mail->Password = "your password";
    $mail->SetFrom('email.xyz', 'Twitter-data');
    $mail->Subject = "Followers Data";
    $mail->AltBody = "";
    $mail->AddAddress("send mail address");
=======
    $mail->Username = "Email";  
    $mail->Password = "pass";
    $mail->SetFrom('twiproj@visana.xyz', 'Twitter-data');
    $mail->Subject = "Followers Data";
    $mail->AltBody = "";
    $mail->AddAddress("Enter address here");
>>>>>>> 065093bcbf84778ed1d361a2a686a08ee4531d92
    $mail->MsgHTML("Your requested follower data is in file attached below");
   // $mail->AddAttachment(__DIR__."/".FILE_NAME.'.'.$format);

     if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
     } else {
        echo "Message has been sent";
     }

?>
