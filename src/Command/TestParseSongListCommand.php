<?php

namespace App\Command;

use App\Clock\AlbumDisplayService;
use App\Discography\Content\Album\AlbumCoverImportService;
use App\Discography\Content\Album\AlbumService;
use App\Discography\Content\Album\ReleaseType;
use App\Discography\Import\ImportService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'test:parse_song_list',
    description: 'Add a short description for your command',
)]
class TestParseSongListCommand extends Command
{
    public function __construct(
//        private readonly ImportService $importService,
        private readonly AlbumCoverImportService $albumImportService,
        private readonly AlbumService $albumService,
        private readonly AlbumDisplayService  $albumDisplayService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->albumDisplayService->displayDisplayOnTctMonitorViaPythonLibrary();
//        $meta = new AlbumMetaData();
//        $meta->setDetailUrl('/diskografie/13');
//        $this->importService->importAlbum($meta);
        $album = $this->albumService->findByNameAndType('Für immer', ReleaseType::SINGLE);
//
        $this->albumImportService->importCoverForAlbum($album);

        return Command::SUCCESS;
    }
}
