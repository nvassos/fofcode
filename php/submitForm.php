<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

chdir(dirname(__FILE__));

require 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

$to = 'admin@devils-heaven.com';

//$mail->SMTPDebug  = 2;
$mail->isSMTP();
$mail->CharSet = 'utf-8';
$mail->Host = 'mail12.world4you.com';
$mail->Port = 25;
$mail->SMTPAuth = true;
$mail->Username = 'admin@devils-heaven.com';
$mail->Password = 'deviL4';
//$mail->SMTPSecure = 'ssl';

$mail->From = $to;
$mail->FromName = 'Faces of Finance Email';
$mail->addAddress($to);
$mail->addReplyTo($_POST['from']);

$mail->WordWrap = 200;
$mail->isHTML(true);

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b><br><br>Phone Number: ' . $_POST['phone'];
$mail->AltBody = 'Phone Number: ' . $_POST['phone'];

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
