<?php

declare(strict_types=1);

namespace App\Discography\Import\Parser;

class SongMetaData
{
    /**
     * @var array<string>
     */
    private array $music = [];

    /**
     * @var array<string>
     */
    private array $text = [];

    public function getMusic(): array
    {
        return $this->music;
    }

    public function setMusic(array $music): SongMetaData
    {
        $this->music = $music;
        return $this;
    }

    public function getText(): array
    {
        return $this->text;
    }

    public function setText(array $text): SongMetaData
    {
        $this->text = $text;
        return $this;
    }
}
