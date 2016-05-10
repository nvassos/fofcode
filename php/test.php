<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
/*$data = file_get_contents('https://graph.facebook.com/10203465954441860/picture?width=378&height=378');
echo $data;*/
$ch = curl_init();
//curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_URL, 'https://fbcdn-sphotos-b-a.akamaihd.net/hphotos-ak-xpa1/t31.0-8/p180x540/10362801_10203307677005023_1530097718619306960_o.jpg');
//curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
$data = curl_exec($ch);
curl_close($ch);

//$data = file_get_contents('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpa1/v/t1.0-1/c48.16.203.203/250234_203979999646605_6448082_n.jpg?oh=0e569fcebf749f4f27fe1142c60ce379&oe=54F07EE4&__gda__=1422168614_7e16992fabbf5011d4aa293ea10c1083');

var_dump($data);
