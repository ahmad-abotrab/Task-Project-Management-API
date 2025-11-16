<?php

namespace App\Storage;

class JsonStorage
{
    public function __construct(
        private string $pathFile
    ) {}

    public function load(): array
    {
        // There are problem in path (not found for example)
        if (!file_exists($this->pathFile)) {
            return [];
        }

        //read File
        $json = file_get_contents($this->pathFile);

        // some problem happend in read file
        if (!$json || $json == '') return [];

        // decode json
        $data = json_decode($json, true);

        // problem happend in decoding
        if (!is_array($data)) return [];

        return $data;
    }

    public function save(array $data): void
    {
        $json = json_encode($data);
        file_put_contents($this->pathFile, $json);
    }
}
