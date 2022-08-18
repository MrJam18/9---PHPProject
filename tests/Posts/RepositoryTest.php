<?php
declare(strict_types=1);

namespace Jam\PhpProject\tests\Posts;

use Jam\PhpProject\Common\UUID;
use Jam\PhpProject\DataBase\Post;
use Jam\PhpProject\Exceptions\NotFoundException;
use Jam\PhpProject\Repositories\DBPostsRepository;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase {

    function testItSaveCalling():void
    {
        $repo = $this->getSaveMockRepo(true);
        $post = new Post(UUID::random(), UUID::random(), '123', 'one two three');
        $this->assertTrue($repo->save($post));
    }

    function testItFindPostByUuid():void
    {
        $expected = new Post(UUID::random(), UUID::random(), 'one two free', 'four five six');
        $postArray = [
            'uuid' => (string)$expected->getUUID(),
            'author_uuid' => (string)$expected->getAuthorUUID(),
            'title' => $expected->getHeader(),
            'text' => $expected->getText()
            ];
        $repository = $this->getMockRepo($postArray);
        $actual = $repository->get($expected->getUUID());
        $this->assertEquals($expected, $actual);
    }

    function testItThrowExceptionIfNotFound():void
    {
        $uuid = UUID::random();
        $repo = $this->getMockRepo(false);
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Cannot get post: $uuid");
        $repo->get($uuid);
    }

    function getMockRepo( array|bool $fetchData):DBPostsRepository
        {
            $connectionMock = $this->createStub(\PDO::class);
            $statementStub = $this->createStub(\PDOStatement::class);
            $connectionMock->method('prepare')->willReturn($statementStub);
            $statementStub->method('fetch')->willReturn($fetchData);
            return new DBPostsRepository($connectionMock);
        }
    function getSaveMockRepo(array|bool $executeData):DBPostsRepository
    {
        $connectionMock = $this->createStub(\PDO::class);
        $statementStub = $this->createStub(\PDOStatement::class);
        $connectionMock->method('prepare')->willReturn($statementStub);
        $statementStub->method('execute')->willReturn($executeData);
        return new DBPostsRepository($connectionMock);
    }




}