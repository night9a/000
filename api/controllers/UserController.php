<?php
require_once '../models/User.php';

class UserController {
    private $db;
    private $requestMethod;
    private $user;

    public function __construct($db, $requestMethod) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->user = new User($db);
    }

    public function processRequest() {
        switch ($this->requestMethod) {
            case 'GET':
                if ($_SERVER['REQUEST_URI'] === '/user/dashboard') {
                    $this->getDashboard();
                }
                break;
            default:
                $this->notFoundResponse();
                break;
        }
    }

    private function getDashboard() {
        session_start();
        if ($_SESSION['role'] !== 'user') {
            $this->unauthorizedResponse();
            return;
        }

        include_once '../views/user/dashboard.php';
    }

    private function unauthorizedResponse() {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['message' => 'Unauthorized']);
    }

    private function notFoundResponse() {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['message' => 'Endpoint not found']);
    }
}
