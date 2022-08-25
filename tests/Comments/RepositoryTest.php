<?php
declare(strict_types=1);

namespace Jam\PhpProject\tests\Comments;

use GeekBrains\Blog\UnitTests\DummyLogger;
use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Comment;
use Jam\PhpProject\Exceptions\NotFoundException;
use Jam\PhpProject\Repositories\DBCommentsRepository;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase {

   function testItSaveCalling()
   {
       $repo = $this->getSaveMockRepo(true);
       $comment = new Comment(UUID::random(), UUID::random(), UUID::random(), 'justText');
       $repo->save($comment);
       $this->assertNull(null);
   }

   function testItFindCommentByUuid()
   {
       $expected = new Comment(UUID::random(), UUID::random(), UUID::random(), 'one two free');
       $commentFetching = [
           'uuid' => (string)$expected->getUUID(),
           'author_uuid' => (string)$expected->getAuthorUUID(),
           'post_uuid' => (string)$expected->getPostUUID(),
           'text' => $expected->getText()
       ];
       $repository = $this->getMockRepo($commentFetching);
       $actual = $repository->get($expected->getUUID());
       $this->assertEquals($expected, $actual);
   }

    function testItThrowExceptionIfNotFound():void
    {
        $uuid = UUID::random();
        $repo = $this->getMockRepo(false);
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Cannot found row in table comments where uuid = $uuid");
        $repo->get($uuid);
    }

   function getMockRepo(array|bool $fetchData):DBCommentsRepository
   {
       $logger = new DummyLogger();
       $connectionMock = $this->createStub(\PDO::class);
       $statementStub = $this->createStub(\PDOStatement::class);
       $connectionMock->method('prepare')->willReturn($statementStub);
       $statementStub->method('fetch')->willReturn($fetchData);
       return new DBCommentsRepository($connectionMock, $logger);
   }
    function getSaveMockRepo(array|bool $executeData):DBCommentsRepository
    {
        $logger = new DummyLogger();
        $connectionMock = $this->createStub(\PDO::class);
        $statementStub = $this->createStub(\PDOStatement::class);
        $connectionMock->method('prepare')->willReturn($statementStub);
        $statementStub->method('execute')->willReturn($executeData);
        return new DBCommentsRepository($connectionMock, $logger);
    }
}