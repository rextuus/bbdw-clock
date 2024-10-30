<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:restart',
    description: 'Restart the system',
)]
class RestartCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Restarting Raspberry Pi');

        $result = shell_exec('sudo /sbin/reboot');

        if ($result === null) {
            $io->success('Raspberry Pi is restarting...');
            return Command::SUCCESS;
        } else {
            $io->error('Failed to restart the Raspberry Pi.');
            return Command::FAILURE;
        }
    }
}
