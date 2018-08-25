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
    $mail->Username = "Email";  
    $mail->Password = "pass";
    $mail->SetFrom('twiproj@visana.xyz', 'Twitter-data');
    $mail->Subject = "Followers Data";
    $mail->AltBody = "";
    $mail->AddAddress("Enter address here");
    $mail->MsgHTML("Your requested follower data is in file attached below");
    // $mail->AddAttachment(__DIR__."/".FILE_NAME.'.'.$format);

        if (!$mail->Send()) {
        echo "Mailer Error: ".$mail->ErrorInfo;
        } else {
        echo "Message has been sent";
        }

?>
