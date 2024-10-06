<?php

namespace App\Command;

use App\Clock\Content\Setting\AlbumDisplayMode;
use App\Clock\Content\Setting\SettingService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'test:change-settings',
    description: 'Add a short description for your command',
)]
class TestChangeSettingsCommand extends Command
{
    public function __construct(private readonly SettingService $settingService)
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->addArgument('displayMode', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $displayMode = $input->getArgument('displayMode');
        $displayModeType = AlbumDisplayMode::tryFrom($displayMode);
        if ($displayModeType === null) {
            $io->error(sprintf('Non existing display mode: %s', $displayMode));

            $io->error(
                sprintf(
                    'Please use one of %s',
                    implode(
                        '|',
                        array_map(function (AlbumDisplayMode $mode) {
                            return $mode->value;
                        },
                            AlbumDisplayMode::cases()
                        )
                    )
                )
            );
            return Command::INVALID;
        }

        $this->settingService->setCurrentAlbumDisplayMode($displayModeType);

        return Command::SUCCESS;
    }
}
