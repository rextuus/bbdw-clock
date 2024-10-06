<?php

namespace App\Command;

use App\Clock\AudioService;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:import-audios',
    description: 'Add a short description for your command',
)]
class ImportAudiosCommand extends Command
{
    public function __construct(private readonly AudioService $audioService, private MessageBusInterface $messageBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'directory',
            InputOption::VALUE_REQUIRED,
            'Directory path to scan for mp3 files',
            '/home/wolfgang/Documents/programming/bbdw-clock' // default value
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $directory = $input->getArgument('directory');

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'mp3') {
                continue;
            }
            $path = $file->getPathname();
            $relativePath = str_replace($directory, '', $path);
            $ident = str_replace(['/', '.mp3', ' '], ['_', '', '_'], $relativePath);
            $ident = substr($ident, 1, strlen($ident));
            $this->audioService->createAudioByMp3File($path, $ident);
        }
//        $this->audioService->playAudio($this->audioService->getAudio(2));

//        $this->messageBus->dispatch(new PlaySoundMessage(2));

        return Command::SUCCESS;
    }
}
