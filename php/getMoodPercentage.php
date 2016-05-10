<?php
include 'config.php';
include 'super_pdo/src/database.php';

if (isset($_GET['gender']) && isset($_GET['mood'])) {
	$gender = $_GET['gender'];
	$mood = $_GET['mood'];
} else {
	die();
}

$db = new Database();

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
