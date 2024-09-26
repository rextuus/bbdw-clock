<?php

namespace App\MessageHandler;

use App\Discography\Import\ImportService;
use App\Message\ParseAlbumMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ParseAlbumMessageHandler
{
    public function __construct(
        private readonly ImportService $importService,
    ) {
    }

    public function __invoke(ParseAlbumMessage $message): void
    {
        sleep(2);
        $this->importService->importAlbum($message->getMetaData());
    }
}
