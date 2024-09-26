<?php

namespace App\Command;

use App\Clock\LyricGameProcessor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test-game-processor',
    description: 'Add a short description for your command',
)]
class TestGameProcessorCommand extends Command
{
    public function __construct(private readonly LyricGameProcessor $lyricGameProcessor)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->lyricGameProcessor->setNewRandomLyric();

        return Command::SUCCESS;
    }
}
