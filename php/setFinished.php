<?php
include 'config.php';
include 'super_pdo/src/database.php';

if (isset($_GET['id'])) {
	$id = (int) $_GET['id'];
	$gender = $_GET['gender'];
	$mood = $_GET['mood'];
} else {
	die();
}

$db = new Database();
$db->update(array(
	'finished' => 1,
	'gender' => $gender,
	'mood' => $mood
), DEFAULTTABLE, $id, 'id');

//calculate percentage and echo it
//get all
$db->query('SELECT * FROM ' . DEFAULTTABLE . ' WHERE finished = 1 AND gender = \'' . $gender . '\'');
$db->execute();
$countAll = $db->rowCount();
//get moody ones
$db->query('SELECT * FROM ' . DEFAULTTABLE . ' WHERE finished = 1 AND gender = \'' . $gender . '\' AND mood = \'' . $mood . '\'');
$db->execute();
$countMood = $db->rowCount();
echo intval(($countMood / $countAll) * 100);
