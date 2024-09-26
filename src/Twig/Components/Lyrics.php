<?php

namespace App\Twig\Components;

use App\Entity\Lyric;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Lyrics
{
    use DefaultActionTrait;

    /**
     * @var array<Lyric>
     */
    public array $lyrics = [];

    public function getRandomNumber(): int
    {
        return rand(0, 1000);
    }
}
