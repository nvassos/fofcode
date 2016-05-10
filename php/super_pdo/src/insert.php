<?php
//error_reporting(E_ALL);
//ini_set('display_errors','On');
require_once 'config.php';
require_once 'database.php';

//add ip and additional field
$data = $_REQUEST;
$data['ip'] = $_SERVER['REMOTE_ADDR'];
$data['additional_field'] = 'whatever';

//write to database
$database = new Database();
echo $database->insert($data, DEFAULTTABLE);
?>