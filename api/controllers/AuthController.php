<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
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
            case 'POST':
                if ($_SERVER['REQUEST_URI'] === '/api/register') {
                    $this->register();
                } elseif ($_SERVER['REQUEST_URI'] === '/api/login') {
                    $this->login();
                }
                break;
            default:
                $this->notFoundResponse();
                break;
        }
    }

    private function register() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if (!$this->validateUser($input)) {
            $this->unprocessableEntityResponse();
            return;
        }

        $this->user->first_name = $input['first_name'];
        $this->user->last_name = $input['last_name'];
        $this->user->email = $input['email'];
        $this->user->password = password_hash($input['password'], PASSWORD_BCRYPT);
        $this->user->role = 'user';
        $this->user->created_at = date('Y-m-d H:i:s');

        if ($this->user->register()) {
            $this->createdResponse();
        } else {
            $this->internalServerErrorResponse();
        }
    }

    private function login() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $this->user->email = $input['email'];
        $this->user->password = $input['password'];

        $user = $this->user->login();
        if ($user) {
            session_start();
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            $this->okResponse(['message' => 'Login successful']);
        } else {
            $this->unauthorizedResponse();
        }
    }

    private function validateUser($input) {
        return isset($input['first_name'], $input['last_name'], $input['email'], $input['password']);
    }

    private function unprocessableEntityResponse() {
        header('HTTP/1.1 422 Unprocessable Entity');
        echo json_encode(['message' => 'Invalid input']);
    }

    private function unauthorizedResponse() {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['message' => 'Unauthorized']);
    }

    private function createdResponse() {
        header('HTTP/1.1 201 Created');
        echo json_encode(['message' => 'User created successfully']);
    }

    private function okResponse($data) {
        header('HTTP/1.1 200 OK');
        echo json_encode($data);
    }

    private function internalServerErrorResponse() {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['message' => 'An error occurred']);
    }

    private function notFoundResponse() {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['message' => 'Endpoint not found']);
    }
}
