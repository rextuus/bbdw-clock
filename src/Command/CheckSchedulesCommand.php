<?php

namespace App\Command;

use App\Clock\Content\ShutdownSchedule\ShutdownScheduleService;
use App\Clock\PowerManagementService;
use DateInterval;
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:check-schedules',
    description: 'Add a short description for your command',
)]
class CheckSchedulesCommand extends Command
{
    public function __construct(
        private ShutdownScheduleService $shutdownScheduleService,
        private PowerManagementService $powerManagementService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $scheduleList = $this->shutdownScheduleService->getScheduleList();
        $now = new DateTime();
        $oneMinuteBefore = (clone $now)->sub(new DateInterval('PT1M'));
        $oneMinuteAfter = (clone $now)->add(new DateInterval('PT1M'));

        foreach ($scheduleList->getSchedules() as $schedule) {
            $restartTime = $schedule->getRestartTime();
            $shutdownTime = $schedule->getShutdownTime();

            if ($restartTime > $oneMinuteBefore && $restartTime < $oneMinuteAfter) {
                $this->powerManagementService->turnDisplayOn();
            }

            if ($shutdownTime > $oneMinuteBefore && $shutdownTime < $oneMinuteAfter) {
                $this->powerManagementService->turnDisplayOff();
            }
        }

        return Command::SUCCESS;
    }
}
