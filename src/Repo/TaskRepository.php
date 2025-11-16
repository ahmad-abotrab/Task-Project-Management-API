<?php

namespace App\Repo;

use App\Storage\JsonStorage;
use App\Task;

class TaskRepository
{

    public function __construct(
        private JsonStorage $storage
    ) {}

    public function all(): array
    {
        $row = $this->storage->load();
        return array_map(
            fn(array $item) => Task::fromArray($item),
            $row,
        );
    }
    public function nextId(): int
    {
        $tasks = $this->all();
        $max = 0;
        foreach ($tasks as $task) {
            if ($task->id > $max) {
                $max = $task->id;
            }
        }
        return $max + 1;
    }

    public function add(Task $task): void
    {
        $rows = $this->storage->load();
        $rows[] = $task->toArray();
        $this->storage->save($rows);
    }

    public function update(Task $task): void
    {
        $rows = $this->storage->load();

        foreach ($rows as &$row) {
            if ($row['id'] === $task->id) {
                $row = $task->toArray();
                break;
            }
        }

        $this->storage->save($rows);
    }

    public function delete(int $id): void
    {
        $rows = $this->storage->load();

        $rows = array_filter($rows, fn($row) => $row['id'] !== $id);

        $this->storage->save(array_values($rows));
    }

    public function find(int $id): ?Task
    {
        foreach ($this->all() as $task) {
            if ($task->id === $id) {
                return $task;
            }
        }
        return null;
    }
}
