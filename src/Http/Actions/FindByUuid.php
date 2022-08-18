<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http\Actions;

use Exception;
use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Http\AbstractResponse;
use Jam\PhpProject\Http\ErrorResponse;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Http\SuccessfulResponse;
use Jam\PhpProject\Interfaces\IAction;
use Jam\PhpProject\Interfaces\IRepository;

class FindByUuid implements IAction
{
    public function __construct(
        private readonly IRepository $repository
    )
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $uuid = $request->query('uuid');
            $uuid = new UUID($uuid);
            $DBObject = $this->repository->get($uuid);
        } catch (Exception $e) {
            return new ErrorResponse($e->getMessage());
        }
// Возвращаем успешный ответ
        return new SuccessfulResponse([
            'data' => (string) $DBObject
        ]);
    }
}