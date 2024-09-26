<?php

namespace App\Command;

use App\Discography\Content\God\GodService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:init-best-band-in-the-world',
    description: 'Init members of best band in the world to db',
)]
class InitBestBandInTheWorldCommand extends Command
{
    public function __construct(private GodService $godService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->godService->initBestBandInTheWorld();

        return Command::SUCCESS;
    }
}
