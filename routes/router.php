<?php
declare(strict_types=1);


use Jam\PhpProject\Exceptions\HTTPException;
use Jam\PhpProject\Http\ErrorResponse;
use Jam\PhpProject\Http\Request;
use Psr\Log\LoggerInterface;

$root = $_SERVER['DOCUMENT_ROOT'];
$container = require $root . '/bootstrap.php';
$routes = require __DIR__ . '/routes.php';
$method = $_SERVER['REQUEST_METHOD'];
$request = new Request($_SERVER, $_GET, file_get_contents('php://input'));
$logger = $container->get(LoggerInterface::class);
try {
    $path = $request->path();
} catch (HTTPException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

if (!array_key_exists($method, $routes)) {
    $message = "Route not found: $method $path";
    $logger->notice($message);
    (new ErrorResponse($message))->send();
    return;
}
if (!array_key_exists($path, $routes[$method])) {
    $message = "Route not found: $method $path";
    $logger->notice($message);
    (new ErrorResponse($message))->send();
    return;
}
$actionClassName = $routes[$method][$path];
try {
    $action = $container->get($actionClassName);
    $response = $action->handle($request);
    $response->send();
} catch (\Exception $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
    (new ErrorResponse($e->getMessage()))->send();
}
