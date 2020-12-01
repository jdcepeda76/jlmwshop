<?php



include("../libs/mailer/src/PHPMailer.php");
include("../libs/mailer/src/SMTP.php");
include("../libs/mailer/src/Exception.php");


    try {
        
        $emailTo = $_POST['email'];
        $subject = $_POST['asunto'];
        $bodyEmail = $_POST['mensaje'];

        $fromemail = 'jlmworkshop2020@gmail.com';
        $fromname = "JLM WorkShop";
        $host = "smtp.gmail.com";
        $port = "587";
        $SMTPAuth = true;
        $SMTPSecure = "tls";
        $password = "jlm2020*";
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug=1;
        $mail->Host = $host;
        $mail->Port = $port;
        $mail->SMTPAuth = $SMTPAuth;
        $mail->SMTPSecure = $SMTPSecure;
        $mail->Username = $fromemail;
        $mail->Password = $password;
        $mail->setFrom($fromemail,$fromname);
        $mail->addAddress($emailTo);
        
        $mail->isHTML(true);
        $mail->Subject = $subject;

        $mail->Body = $bodyEmail;

        if (!$mail->send()){
             echo "no se envio correo"; die();
        }
        echo "Correo enviado con exito!!"; die();



    } catch (Exception $e){

    }
