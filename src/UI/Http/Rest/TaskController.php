<?php

declare(strict_types=1);

namespace App\UI\Http\Rest;

use App\Common\CommandBusInterface;
use App\Application\Task\Command\Builder\AddTaskCommandBuilder;
use App\Application\Task\Command\Builder\ChangeTaskStatusCommandBuilder;
use App\Application\Task\Query\TaskQuery;
use App\Infrastructure\Validation\Task\TaskAddValidator;
use App\Infrastructure\Validation\Task\TaskChangeStatusValidator;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskController extends AbstractController
{
    private CommandBusInterface $commandBus;
    private TaskQuery $taskQuery;
    private TaskAddValidator $taskAddValidator;
    private TaskChangeStatusValidator $taskChangeStatusValidator;

    public function __construct(
        CommandBusInterface $commandBus,
        TaskQuery $taskQuery,
        TaskAddValidator $taskValidator,
        TaskChangeStatusValidator $taskChangeStatusValidator
    ) {
        $this->commandBus = $commandBus;
        $this->taskQuery = $taskQuery;
        $this->taskAddValidator = $taskValidator;
        $this->taskChangeStatusValidator = $taskChangeStatusValidator;
    }

    public function details(string $guid): JsonResponse
    {
        return new JsonResponse($this->taskQuery->getByGuid($guid));
    }

    public function meToday(): JsonResponse
    {
        return new JsonResponse($this->taskQuery->getMyTodayTasks());
    }

    public function add(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $this->taskAddValidator->validate($requestData);
        $guid = Uuid::uuid4()->toString();
        $this->commandBus->dispatch(AddTaskCommandBuilder::buildFromRequestData($guid, $requestData));

        return new JsonResponse(['guid' => $guid]);
    }

    public function changeStatus(Request $request): JsonResponse
    {
        $requestContent = json_decode($request->getContent(), true);
        $requestData = array_merge((array)$requestContent, ['guid' => $request->get('guid')]);
        $this->taskChangeStatusValidator->validate($requestData);

        $this->commandBus->dispatch(ChangeTaskStatusCommandBuilder::buildFromRequestData($requestData));

        return new JsonResponse();
    }
}
