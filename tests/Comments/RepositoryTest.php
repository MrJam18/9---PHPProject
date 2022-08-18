<?php
declare(strict_types=1);

namespace Jam\PhpProject\tests\Comments;

use Jam\PhpProject\Common\Comment;
use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\Exceptions\NotFoundException;
use Jam\PhpProject\Repositories\DBCommentsRepository;
use Jam\PhpProject\Repositories\tests\MockCommentsRepo;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase {

   function testItSaveCalling()
   {
       $repo = new MockCommentsRepo();
       $comment = new Comment(UUID::random(), UUID::random(), UUID::random(), 'justText');
       $repo->save($comment);
       $this->assertTrue($repo->getSaveWasCalled());
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
        $this->expectExceptionMessage("Cannot get comment: $uuid");
        $repo->get($uuid);
    }

   function getMockRepo(array|bool $fetchData):DBCommentsRepository
   {
       $connectionMock = $this->createStub(\PDO::class);
       $statementStub = $this->createStub(\PDOStatement::class);
       $connectionMock->method('prepare')->willReturn($statementStub);
       $statementStub->method('fetch')->willReturn($fetchData);
       return new DBCommentsRepository($connectionMock);
   }
}