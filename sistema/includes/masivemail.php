<?php

include("../libs/mailer/src/PHPMailer.php");
include("../libs/mailer/src/SMTP.php");
include("../libs/mailer/src/Exception.php");

$mail = new PHPMailer\PHPMailer\PHPMailer();


$mail->isSMTP();
$mail->SMTPDebug = 2; 
$mail->Debugoutput = 'html'; 
$mail->Host = 'smtp.gmail.com'; 
$mail->Port = 587;
$mail->SMTPSecure = 'tls'; 
$mail->SMTPAuth = true;
$mail->Username = "jlmworkshop2020@gmail.com"; 
$mail->Password = "jlm2020*"; 
$db = new mysqli('localhost', 'root', '', 'jlm'); 
if ($db->connect_errno > 0) 
{
    die('Error connect: [' . $db->connect_error . ']'); 
} 

$result = $db->query("SELECT Correo FROM usuarios WHERE estatus = 1");

while($row = $result->fetch_assoc())
{ 
    $mail->setFrom('jlmworkshop2020@gmail.com', 'JLM WorkShop'); 
    $mail->addAddress($row['Correo']); 
    $subject = $_POST['asunto'];
    $mail->Subject = $subject;
    $message = $_POST['mensaje'];
    $mail->msgHTML($message);
    $mail->send(); 
    $mail->ClearAddresses(); 
}
