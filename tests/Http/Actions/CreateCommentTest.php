<?php
declare(strict_types=1);

namespace Http\Actions;

use GeekBrains\Blog\UnitTests\DummyLogger;
use Jam\PhpProject\Exceptions\UserNotFoundException;
use Jam\PhpProject\Http\Actions\CreateComment;
use Jam\PhpProject\Http\ErrorResponse;
use Jam\PhpProject\Http\Request;
use Jam\PhpProject\Http\SuccessfulResponse;
use Jam\PhpProject\Repositories\DBCommentsRepository;
use Jam\PhpProject\Repositories\DBPostsRepository;
use Jam\PhpProject\Repositories\DBUsersRepository;
use PHPUnit\Framework\TestCase;

class CreateCommentTest extends TestCase
{
    function testGetSuccess()
    {
        $expected = new SuccessfulResponse(['message' => "comment was saved"]);
        $createComment = $this->getCreateComment();
        $body = '{
          "author_uuid": "f2e66ace-275f-4fbf-8df0-135293add909",
          "post_uuid": "b905c28a-e828-48e1-a4bb-2c03c276f4be",
          "text": "feeeeeeeeeeeeeeee"
            }';
        $request = new Request($_SERVER, [], $body);
        $actual = $createComment->handle($request);
        $this->assertEquals($expected, $actual);
    }

    function testGetUuidError()
    {
        $createComment = $this->getCreateComment();
        $wrongUUID = 'f2e66ace-275f-4fbf-8df0-135293add909123421412214214141421';
        $body = '{
          "author_uuid": ' . '"'. $wrongUUID . '"' . ',
          "post_uuid": "b905c28a-e828-48e1-a4bb-2c03c276f4be",
          "text": "feeeeeeeeeeeeeeee"
            }';
        $request = new Request($_SERVER, [], $body);
        $expected = new ErrorResponse("Malformed UUID: $wrongUUID");
        $actual = $createComment->handle($request);
        $this->assertEquals($expected, $actual);
    }

    function testGetUserNotFoundError()
    {
        $repo = $this->getMockRepo(true);
        $usersRepo = $this->createStub(DBUsersRepository::class);
        $postsRepo = $this->createStub(DBPostsRepository::class);
        $userUuid = 'b905c28a-e828-48e1-a4bb-2c03c276f4be';
        $expectedMessage = "Cannot get user: $userUuid";
        $usersRepo->method('get')->willThrowException(new UserNotFoundException(
            $expectedMessage
        ));
        $createComment = new CreateComment($repo, $usersRepo, $postsRepo);
        $body = '{
          "author_uuid": ' . '"'. $userUuid . '"' . ',
          "post_uuid": "b905c28a-e828-48e1-a4bb-2c03c276f4be",
          "text": "feeeeeeeeeeeeeeee"
            }';
        $request = new Request($_SERVER, [], $body);
        $expected = new ErrorResponse($expectedMessage);
        $actual = $createComment->handle($request);
        $this->assertEquals($expected, $actual);
    }

    function testNotAllDataProvided()
    {
        $createComment = $this->getCreateComment();
        $body = '{
          "author_uuid": "b905c28a-e828-48e1-a4bb-2c03c276f4be",
          "text": "feeeeeeeeeeeeeeee"
            }';
        $request = new Request($_SERVER, [], $body);
        $expected = new ErrorResponse('dont have all necessary keys in request body');
        $actual = $createComment->handle($request);
        $this->assertEquals($expected, $actual);
    }

    function getMockRepo(array|bool $executeData):DBCommentsRepository
    {
        $logger = new DummyLogger();
        $connectionMock = $this->createStub(\PDO::class);
        $statementStub = $this->createStub(\PDOStatement::class);
        $connectionMock->method('prepare')->willReturn($statementStub);
        $statementStub->method('execute')->willReturn($executeData);
        return new DBCommentsRepository($connectionMock, $logger);
    }

    function getCreateComment():CreateComment
    {
        $repo = $this->getMockRepo(true);
        $usersRepo = $this->createStub(DBUsersRepository::class);
        $postsRepo = $this->createStub(DBPostsRepository::class);
        return new CreateComment($repo, $usersRepo, $postsRepo);
    }
}