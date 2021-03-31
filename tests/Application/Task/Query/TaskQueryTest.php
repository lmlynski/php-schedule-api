<?php

declare(strict_types=1);

namespace App\Tests\Application\Task\Query;

use App\Application\Task\Query\Model\TaskView;
use App\Application\Task\Query\TaskQuery;
use App\Application\Task\Query\UserFilter;
use App\Common\UserGuidResolverInterface;
use App\Domain\Task\Task;
use App\Domain\Task\TaskRepositoryInterface;
use App\Infrastructure\Repository\Task\Resolver\TaskReadRepositoryResolverInterface;
use PHPUnit\Framework\TestCase;

class TaskQueryTest extends TestCase
{
    private TaskRepositoryInterface $taskReadRepository;
    private TaskReadRepositoryResolverInterface $taskReadRepositoryResolver;
    private UserGuidResolverInterface $userGuidResolverInterface;
    private TaskQuery $taskQuery;

    public function setUp(): void
    {
        $this->taskReadRepository = $this->createMock(TaskRepositoryInterface::class);
        $this->userGuidResolverInterface = $this->createMock(UserGuidResolverInterface::class);
        $this->taskReadRepositoryResolver = $this->createMock(TaskReadRepositoryResolverInterface::class);
        $this->taskReadRepositoryResolver
            ->expects(self::any())
            ->method('get')
            ->willReturn($this->taskReadRepository);

        $this->taskQuery = new TaskQuery(
            $this->taskReadRepositoryResolver,
            $this->userGuidResolverInterface
        );
    }

    public function tearDown(): void
    {
        unset(
            $this->taskReadRepository,
            $this->requestHeaderUserIdResolver,
            $this->taskReadRepositoryResolver,
            $this->taskQuery
        );
    }

    public function testGetByGuidWithResultInRepoWillReturnTaskView(): void
    {
        $guid = 'some-guid';
        $task = new Task(
            'some-guid',
            'some-title',
            'some-description',
            'some-assigneeId',
            'some-status',
            new \DateTimeImmutable('2021-02-02'),
        );

        $this->taskReadRepository
            ->expects(self::once())
            ->method('findByGuid')
            ->with($guid)
            ->willReturn($task);

        $result = $this->taskQuery->getByGuid($guid);

        self::assertInstanceOf(TaskView::class, $result);
        self::assertSame('some-guid', $result->guid);
        self::assertSame('some-title', $result->title);
        self::assertSame('some-description', $result->description);
        self::assertSame('some-assigneeId', $result->assigneeId);
        self::assertSame('some-status', $result->status);
        self::assertSame('2021-02-02', $result->dueDate);
    }

    public function testGetMyTodayTasksWithResultInRepoWillReturnTaskViewCollection(): void
    {
        $tasks = [
            new Task(
                'some-guid',
                'some-title',
                'some-description',
                'some-assigneeId',
                'some-status',
                new \DateTimeImmutable('2021-02-02'),
            ),
            new Task(
                'some-guid-2',
                'some-title-2',
                'some-description-2',
                'some-assigneeId',
                'some-status-2',
                new \DateTimeImmutable('2021-02-02'),
            )
        ];

        $this->taskReadRepository
            ->expects(self::once())
            ->method('findAllBy')
            ->with(self::isInstanceOf(UserFilter::class))
            ->willReturn($tasks);

        $this->userGuidResolverInterface
            ->expects(self::once())
            ->method('resolve')
            ->willReturn('some-assigneeId');

        $result = $this->taskQuery->getMyTodayTasks();

        self::assertCount(2, $result);
        self::assertInstanceOf(TaskView::class, $result[0]);
        self::assertInstanceOf(TaskView::class, $result[1]);
    }

}
