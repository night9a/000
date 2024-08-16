<?php
$requestUri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

switch ($requestUri) {
    case '/api/register':
    case '/api/login':
        require_once './routes/api.php';
        break;
    case '/admin/dashboard':
        require_once './routes/admin.php';
        break;
    case '/user/dashboard':
        require_once './routes/user.php';
        break;
    default:
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['message' => 'Page not found']);
        break;
}
