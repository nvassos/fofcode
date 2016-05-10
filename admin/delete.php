<?php
//error_reporting(E_ALL);
//ini_set('display_errors','On');
require_once '../php/config.php';
require_once '../php/super_pdo/src/database.php';

$database = new Database();
$database->query('DELETE FROM ' . DEFAULTTABLE . ' WHERE id = :id');
$database->bind(':id', intval($_GET['id']));
$database->execute();
echo 1;
