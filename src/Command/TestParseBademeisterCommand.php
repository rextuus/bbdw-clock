<?php

namespace App\Command;

use App\Discography\Import\ImportService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'test:parse_bademeister',
    description: 'Add a short description for your command',
)]
class TestParseBademeisterCommand extends Command
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
