<?php

namespace App\MessageHandler;

use App\Discography\Import\ImportService;
use App\Message\ParseSongMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ParseSongMessageHandler
{
    public function __construct(
        private readonly ImportService $importService,
    ) {
    }

    public function __invoke(ParseSongMessage $message): void
    {
        sleep(2);
        $this->importService->importSong($message->getSongDetailUrl());
    }
}
