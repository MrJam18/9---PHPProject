<?php
declare(strict_types=1);


// Создаём объект запроса из суперглобальных переменных
use Jam\PhpProject\Exceptions\HTTPException;
use Jam\PhpProject\Http\ErrorResponse;
use Jam\PhpProject\Http\Request;

$router = require_once __DIR__ . '/routes.php';
$method = $_SERVER['REQUEST_METHOD'];
$routes = $router[$method];
$request = new Request($_SERVER, $_GET, file_get_contents('php://input'));
try {
// Пытаемся получить путь из запроса
    $path = $request->path();
} catch (HTTPException) {
// Отправляем неудачный ответ,
// если по какой-то причине
// не можем получить путь
    (new ErrorResponse)->send();
// Выходим из программы
    return;
}

if (!array_key_exists($path, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
}
// Выбираем найденное действие
$action = $routes[$path];
try {
// Пытаемся выполнить действие,
// при этом результатом может быть
// как успешный, так и неуспешный ответ
    $response = $action->handle($request);
    $response->send();
} catch (\Exception $e) {
// Отправляем неудачный ответ,
// если что-то пошло не так
    (new ErrorResponse($e->getMessage()))->send();
}
// Отправляем ответ
