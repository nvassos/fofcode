<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

chdir(dirname(__FILE__));

require 'MailChimp/MailChimp.php';

//$mail->addReplyTo($_POST['from']);

$MailChimp = new \Drewm\MailChimp('0f4c3f1fbd42663123c0052bb4fc20eb-us6');
$result = $MailChimp->call('lists/subscribe', array(
    'id'                => '1c2aa9e047',
    'email'             => array('email' => $_POST['from']),
    'double_optin'      => false,
    'send_welcome'      => false,
));
echo 'subscribed';
//print_r($MailChimp->call('lists/list'));
