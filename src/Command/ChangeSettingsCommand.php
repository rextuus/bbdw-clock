<?php

namespace App\Command;

use App\Clock\Content\Setting\AlbumDisplayMode;
use App\Clock\Content\Setting\SettingService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:change-settings',
    description: 'Add a short description for your command',
)]
class ChangeSettingsCommand extends Command
{
    public function __construct(private readonly SettingService $settingService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->settingService->setCurrentAlbumDisplayMode(AlbumDisplayMode::LOOSE);

        return Command::SUCCESS;
    }
}
