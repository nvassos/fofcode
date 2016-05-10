<?php
include 'config.php';
include 'super_pdo/src/database.php';

if (isset($_GET['id'])) {
	$id = (int) $_GET['id'];
} else {
	die();
}

$db = new Database();
$dbEntry = $db->getSingle(DEFAULTTABLE, $id, 'id');
//$db->update(array('finished' => 1), DEFAULTTABLE, $id, 'id');

$pageURL = 'https://';
if ($_SERVER['SERVER_PORT'] != '80') {
	$pageURL .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'];
} else {
	$pageURL .= $_SERVER['SERVER_NAME'];
}

//use biometrics api
$url = 'https://api.skybiometry.com/fc/faces/detect.json';
$params = array(
    'api_key' => '54db20c7080b4269862ea4cf51404ab8',
    'api_secret' => '4334e69768974dfd8ea26335bf3d038f',
    'urls' => $pageURL . '/uploads/' . $id . '/' . $dbEntry['image_original'],
    'attributes' => 'gender,smiling,mood'
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$rawData = curl_exec($ch);
curl_close($ch);

//var_dump($rawData);
$data = json_decode($rawData);

//map moods to happy/sad
$mood = $data->photos[0]->tags[0]->attributes->mood->value;
if ($mood === 'happy' || $mood === 'surprised') {
	$mood = 'happy';
} else {
	$mood = 'sad';
}

echo json_encode(array(
	'gender' => $data->photos[0]->tags[0]->attributes->gender->value,
	'mood' => $mood
));
