<?php
require_once '../config/Database.php';
require_once '../controllers/UserController.php';

$database = new Database();
$db = $database->connect();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$controller = new UserController($db, $requestMethod);

$controller->processRequest();
