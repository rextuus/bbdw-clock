<?php

namespace App\Twig\Components;

use App\Clock\Content\GameSession\GameSessionService;
use App\File\FileService;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Image
{
    use DefaultActionTrait;

    public string $imageName;

    #[LiveProp(writable: true)]
    public int $max = 1000;

    public function getRandomNumber(): int
    {
        return rand(0, $this->max);
    }

    public function __construct(private GameSessionService $gameSessionService, private FileSErvice $fileService)
    {
        $this->imageName = '';
    }

    public function hydrate(): void
    {
        $this->imageName = $this->gameSessionService->getCurrentSong();
    }
}
