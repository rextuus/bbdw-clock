<?php

namespace App\MessageHandler;

use App\Clock\AudioService;
use App\Clock\Content\GameSession\GameSessionService;
use App\Clock\Content\Setting\SettingService;
use App\Clock\LyricGameProcessor;
use App\Message\NextGameRoundMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class NextGameRoundMessageHandler
{
    public function __construct(
        private readonly GameSessionService $gameSessionService,
        private readonly SettingService $settingService,
        private readonly LyricGameProcessor $lyricGameProcessor
    )
    {
    }

    public function __invoke(NextGameRoundMessage $message): void
    {
        // clear song
        $this->gameSessionService->freeCurrentSongAndLyric();
        // TODO check if you have more than one attempt??
        // TODO check in settings if next should automatically be displayed

        // Event evaluation finished
        $this->lyricGameProcessor->setNewRandomLyric();
    }
}
