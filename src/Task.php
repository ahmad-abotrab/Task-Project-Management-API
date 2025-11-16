<?php

namespace App;

use DateTime;
use App\TaskStatus;

class Task
{
    public function __construct(
        public int $id,
        public string $title,
        public TaskStatus $status,
        public string $created_at,
    ) {}



    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            title: $data['title'],
            status: TaskStatus::from($data['status']),
            created_at: $data['created_at'],
        );
    }
    public function toArray(): array
    {
        return
            [
                'id'         => $this->id,
                'title'      => $this->title,
                'status'     => $this->status instanceof TaskStatus
                    ? $this->status->value
                    : $this->status,
                'created_at' => $this->created_at,
            ];
    }
}
