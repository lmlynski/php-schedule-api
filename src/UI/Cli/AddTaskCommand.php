<?php

namespace App\UI\Cli;

use App\Common\CommandBusInterface;
use App\Application\Task\Command\Builder\AddTaskCommandBuilder;
use App\Infrastructure\Validation\Task\TaskAddValidator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddTaskCommand extends Command
{
    protected static $defaultName = 'app:task:add';
    private CommandBusInterface $commandBus;
    private string $validator;

    public function __construct(CommandBusInterface $commandBus, TaskAddValidator $validator, string $name = null)
    {
        parent::__construct($name);
        $this->commandBus = $commandBus;
        $this->validator = $validator;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add new task.')
            ->addArgument('title', InputArgument::REQUIRED, 'Task title')
            ->addArgument('description', InputArgument::REQUIRED, 'Task description')
            ->addArgument('assigneeId', InputArgument::REQUIRED, 'Task assigneeId')
            ->addArgument('status', InputArgument::REQUIRED, 'Task status')
            ->addArgument('dueDate', InputArgument::REQUIRED, 'Task dueDate');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cliData = [
            'title' => $input->getArgument('title'),
            'description' => $input->getArgument('description'),
            'assigneeId' => $input->getArgument('assigneeId'),
            'status' => $input->getArgument('status'),
            'dueDate' => $input->getArgument('dueDate'),
        ];
        $this->validator->validate($cliData);
        $guid = Uuid::uuid4()->toString();

        $this->commandBus->dispatch(AddTaskCommandBuilder::buildFromRequestData($guid, $cliData));

        $outputStyle = new SymfonyStyle($input, $output);
        $outputStyle->success(sprintf('New task successfully added, guid: "%s".', $guid));

        return Command::SUCCESS;
    }
}
