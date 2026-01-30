<?php
ob_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

function respond($success, $message, $data = null, $code = 200)
{
    if (ob_get_length()) ob_clean();
    http_response_code($code);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

$controller = $_GET['controller'] ?? null;
$action = $_GET['action'] ?? null;

if (!$controller || !$action) {
    respond(false, 'Invalid request', null, 400);
}

$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = __DIR__ . '/Controller/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    respond(false, 'Controller not found', null, 404);
}

require_once $controllerFile;

$instance = new $controllerName();

if (!method_exists($instance, $action)) {
    respond(false, 'Action not found', null, 404);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = $_POST;
}

$instance->$action($input);<?php
ob_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

function respond($success, $message, $data = null, $code = 200)
{
    if (ob_get_length()) ob_clean();
    http_response_code($code);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

$controller = $_GET['controller'] ?? null;
$action = $_GET['action'] ?? null;

if (!$controller || !$action) {
    respond(false, 'Invalid request', null, 400);
}

$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = __DIR__ . '/Controller/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    respond(false, 'Controller not found', null, 404);
}

require_once $controllerFile;

$instance = new $controllerName();

if (!method_exists($instance, $action)) {
    respond(false, 'Action not found', null, 404);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = $_POST;
}

$instance->$action($input);