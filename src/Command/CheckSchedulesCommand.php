<?php

namespace App\Command;

use App\Clock\Content\ShutdownSchedule\ShutdownScheduleService;
use App\Clock\PowerManagementService;
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:check-schedules',
    description: 'Checks and applies shutdown and restart schedules',
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
        $output->writeln('Starting schedule check...');
        $scheduleList = $this->shutdownScheduleService->getScheduleList();
        $now = new DateTime();

        $currentHourMinute = $now->format('H:i');
        $output->writeln('Current time: ' . $currentHourMinute);

        foreach ($scheduleList->getSchedules() as $schedule) {
            $restartTime = $schedule->getRestartTime();
            $shutdownTime = $schedule->getShutdownTime();

            $restartHourMinute = $restartTime->format('H:i');
            $shutdownHourMinute = $shutdownTime->format('H:i');

            $output->writeln("Checking schedule: restart at $restartHourMinute, shutdown at $shutdownHourMinute");

            if ($restartHourMinute === $currentHourMinute) {
                $output->writeln('Turning display on.');
                $this->powerManagementService->turnDisplayOn();
            }

            if ($shutdownHourMinute === $currentHourMinute) {
                $output->writeln('Turning display off.');
                $this->powerManagementService->turnDisplayOff();
            }
        }

        $output->writeln('Schedule check completed.');
        return Command::SUCCESS;
    }
}