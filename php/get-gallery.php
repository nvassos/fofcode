<?php
//error_reporting(E_ALL);
//ini_set('display_errors','On');
require_once 'config.php';
require_once 'super_pdo/src/database.php';

$database = new Database();
$database->query('SELECT * FROM ' . DEFAULTTABLE . ' WHERE finished = 1 ORDER BY id DESC');
echo json_encode($database->resultset());
