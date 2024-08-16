<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$database = new Database();
$db = $database->connect();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$controller = new AuthController($db, $requestMethod);

$controller->processRequest();
