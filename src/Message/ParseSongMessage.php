<?php

namespace App\Message;

final class ParseSongMessage
{
    public function __construct(private readonly string $songDetailUrl)
    {
    }

    public function getSongDetailUrl(): string
    {
        return $this->songDetailUrl;
    }
}
