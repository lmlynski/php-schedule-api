<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Repository\Task;

use App\Application\Task\Query\UserFilter;
use App\Domain\Task\Exception\TaskNotFoundException;
use App\Domain\Task\Task;
use App\Infrastructure\Repository\Task\MysqlTaskRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

class MysqlTaskRepositoryTest extends TestCase
{
    private Connection $connection;
    private EntityManager $entityManager;
    private MysqlTaskRepository $repository;

    public function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->entityManager
            ->expects(self::any())
            ->method('getConnection')
            ->willReturn($this->connection);

        $this->repository = new MysqlTaskRepository($this->entityManager);
    }

    public function tearDown(): void
    {
        unset($this->connection, $this->entityManager, $this->repository);
    }

    public function testFindByGuidWithNoTaskFoundWillThrowTaskNotFoundException(): void
    {
        self::expectException(TaskNotFoundException::class);
        self::expectExceptionMessage('Task for guid "some-guid" not found');

        $guid = 'some-guid';

        $this->connection
            ->expects(self::once())
            ->method('fetchAssociative')
            ->with(
                'SELECT * FROM task WHERE guid = :guid',
                [
                    'guid' => $guid
                ]
            )
            ->willReturn([]);

        $this->repository->findByGuid($guid);
    }

    public function testFindByGuidWithTaskFoundWillReturnTaskObject(): void
    {
        $guid = 'some-guid';
        $taskFromDb = [
            'guid' => 'some-guid',
            'title' => 'some-title',
            'description' => 'some-description',
            'assigneeId' => 'some-assigneeId',
            'status' => 'some-status',
            'dueDate' => '2021-02-02',
        ];

        $this->connection
            ->expects(self::once())
            ->method('fetchAssociative')
            ->with(
                'SELECT * FROM task WHERE guid = :guid',
                [
                    'guid' => $guid
                ]
            )
            ->willReturn($taskFromDb);

        $result = $this->repository->findByGuid($guid);

        self::assertInstanceOf(Task::class, $result);
        self::assertSame('some-guid', $result->getGuid());
        self::assertSame('some-title', $result->getTitle());
        self::assertSame('some-description', $result->getDescription());
        self::assertSame('some-assigneeId', $result->getAssigneeId());
        self::assertSame('some-status', $result->getStatus());
        self::assertSame('2021-02-02', $result->getDueDate()->format('Y-m-d'));
    }

    public function testFindAllBydWithFilterCriteriaAndTasksFoundWillReturnCollection(): void
    {
        $tasksFromDb = [
            [
                'guid' => 'some-guid',
                'title' => 'some-title',
                'description' => 'some-description',
                'assigneeId' => 'some-assigneeId',
                'status' => 'new',
                'dueDate' => '2021-02-02',
            ],
            [
                'guid' => 'some-guid-2',
                'title' => 'some-title-2',
                'description' => 'some-description-2',
                'assigneeId' => 'some-assigneeId-2',
                'status' => 'new',
                'dueDate' => '2021-02-02',
            ]
        ];

        $this->connection
            ->expects(self::once())
            ->method('fetchAllAssociative')
            ->with('SELECT * FROM task WHERE status = \'new\' LIMIT 10 OFFSET 0')
            ->willReturn($tasksFromDb);

        $result = $this->repository->findAllBy(
            (new UserFilter())->setFieldCondition('status', 'new')
        );

        self::assertCount(2, $result);
        self::assertInstanceOf(Task::class, $result[0]);
        self::assertInstanceOf(Task::class, $result[1]);
    }

    public function testFindAllBydWithFilterCriteriaAndNoTasksFoundWillReturnEmptyArray(): void
    {
        $tasksFromDb = [];

        $this->connection
            ->expects(self::once())
            ->method('fetchAllAssociative')
            ->with('SELECT * FROM task WHERE status = \'new\' LIMIT 10 OFFSET 0')
            ->willReturn($tasksFromDb);

        $result = $this->repository->findAllBy(
            (new UserFilter())->setFieldCondition('status', 'new')
        );

        self::assertCount(0, $result);
        self::assertSame([], $result);
    }
}
