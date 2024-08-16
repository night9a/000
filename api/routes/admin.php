<?php
require_once '../config/Database.php';
require_once '../controllers/AdminController.php';

$database = new Database();
$db = $database->connect();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$controller = new AdminController($db, $requestMethod);

$controller->processRequest();
