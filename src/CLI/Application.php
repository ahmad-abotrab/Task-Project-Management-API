<?php

namespace App\CLI;

use App\Repo\TaskRepository;
use App\Task;
use App\TaskStatus;


class Application
{
    public function __construct(private TaskRepository $repo) {}

    public function add(array $args)
    {
        $title = $args[2];
        if (!$title) {
            echo "Usage task add your_task";
            return;
        }
        $id = $this->repo->nextId();
        $task = new Task(
            id: $id,
            title: $title,
            status: TaskStatus::Todo,
            created_at: date("YYYY-mm-dd:hh:MM:ss")
        );
        $this->repo->add($task);
        echo "Task #{$task->id} added\n";
    }
    public function list(array $args)
    {
        $statusFilter = $argv[2] ?? null; // todo|done|in_progress|null
        $all = $this->repo->all();

        foreach ($all as $task) {
            if ($statusFilter && $task->status !== $statusFilter) {
                continue;
            }

            echo "[{$task->id}] ({$task->status->value}) {$task->title}\n";
        }
    }
    public function update(array $args) {}
    public function run(array $args): void
    {
        $command = $args[1] ?? null;
        switch ($command) {
            case 'add':
                $this->add($args);
                break;

            case 'list':
                $this->list($args);
                break;
            case 'update':
                $this->update($args);
                break;
            default:
                echo "command not available";
                break;
        }
    }
}
