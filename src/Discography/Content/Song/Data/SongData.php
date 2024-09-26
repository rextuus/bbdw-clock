<?php

declare(strict_types=1);

namespace App\Discography\Content\Song\Data;

class SongData
{
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): SongData
    {
        $this->name = $name;
        return $this;
    }
}
