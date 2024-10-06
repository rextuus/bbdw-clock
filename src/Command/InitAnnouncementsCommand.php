<?php

namespace App\Command;

use App\Clock\AudioService;
use App\Clock\Content\Announcement\AnnouncementService;
use App\Clock\Content\Announcement\AnnouncementType;
use App\Clock\Content\Announcement\Data\AnnouncementData;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:init-announcements',
    description: 'Add a short description for your command',
)]
class InitAnnouncementsCommand extends Command
{
    public function __construct(
        private readonly AudioService $audioService,
        private readonly AnnouncementService $announcementService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $audio = $this->audioService->createAudioByMp3File(
            '/home/wolfgang/Documents/programming/bbdw-clock/test.mp3',
            'test'
        );

        $data = new AnnouncementData();
        $data->setAnnouncementType(AnnouncementType::TO_LATE);
        $data->setIdentifier('??');
        $data->setUseCounter(0);
        $data->setLastUsage(null);
        $data->setAudio($audio);

        return Command::SUCCESS;
    }
}
