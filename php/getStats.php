<?php
include 'config.php';
include 'super_pdo/src/database.php';

$arReturn = array(
  'male' => array('happy' => 0, 'sad' => 0),
  'female' => array('happy' => 0, 'sad' => 0)
);
$db = new Database();
//calculate percentage and echo it
$db->query('SELECT mood, COUNT(mood) as count FROM ' . DEFAULTTABLE . ' WHERE finished = 1 AND gender = \'male\' GROUP BY mood ORDER BY mood asc');
$resultMale = $db->resultset();
for ($i = 0; $i < count($resultMale); $i++) {
  $arReturn['male'][$resultMale[$i]['mood']] = intval($resultMale[$i]['count']);
}
$db->query('SELECT mood, COUNT(mood) as count FROM ' . DEFAULTTABLE . ' WHERE finished = 1 AND gender = \'female\' GROUP BY mood ORDER BY mood asc');
$resultFemale = $db->resultset();
for ($i = 0; $i < count($resultFemale); $i++) {
  $arReturn['female'][$resultFemale[$i]['mood']] = intval($resultFemale[$i]['count']);
}
echo json_encode($arReturn);
//echo intval(($countMood / $countAll) * 100);
