<?php
declare(strict_types=1);


use Jam\PhpProject\Exceptions\HTTPException;
use Jam\PhpProject\Http\ErrorResponse;
use Jam\PhpProject\Http\Request;

$root = $_SERVER['DOCUMENT_ROOT'];
$container = require $root . '/bootstrap.php';
$routes = require __DIR__ . '/routes.php';
$method = $_SERVER['REQUEST_METHOD'];
$request = new Request($_SERVER, $_GET, file_get_contents('php://input'));
try {
    $path = $request->path();
} catch (HTTPException) {
    (new ErrorResponse)->send();
    return;
}

if (!array_key_exists($method, $routes)) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}
$actionClassName = $routes[$method][$path];
try {
    $action = $container->get($actionClassName);
    $response = $action->handle($request);
    $response->send();
} catch (\Exception $e) {
    (new ErrorResponse($e->getMessage()))->send();
}
