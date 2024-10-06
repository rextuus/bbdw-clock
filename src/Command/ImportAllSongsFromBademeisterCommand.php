<?php

namespace App\Command;

use App\Discography\Import\ImportService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'test:import-all-songs-from-bademeister',
    description: 'Crawl bademeister.de and stores all found songs inclusive their lyrics in db',
)]
class ImportAllSongsFromBademeisterCommand extends Command
{
    public function __construct(
        private readonly ImportService $importService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->importService->importSongList();

        return Command::SUCCESS;
    }
}
