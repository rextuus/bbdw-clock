<?php

namespace App\Command;

use App\Discography\Content\Album\AlbumCoverImportService;
use App\Discography\Content\Album\AlbumService;
use App\Discography\Content\Album\ReleaseType;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-album-cover',
    description: 'Add a short description for your command',
)]
class LoadAlbumCoverCommand extends Command
{
    public function __construct(
        private readonly AlbumCoverImportService $albumImportService,
        private readonly AlbumService $albumService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $albums = $this->albumService->getAll();

        foreach ($albums as $album) {
            $this->albumImportService->importCoverForAlbum($album);
        }

        return Command::SUCCESS;
    }
}
