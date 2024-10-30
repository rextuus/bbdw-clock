<?php

declare(strict_types=1);

namespace App\Clock;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\KernelInterface;

class PowerManagementService
{

    public function __construct(
        #[Autowire('%env(POWER_SWITCH_SCRIP_PATH)%')] private readonly string $scriptPath,
        private readonly KernelInterface $kernel
    ) {
    }

    public function turnDisplayOn(): void
    {
        exec('sh ' . $this->scriptPath . ' off');
        $this->restart();
    }

    public function turnDisplayOff(): void
    {
        exec('sh ' . $this->scriptPath . ' on');
    }

    public function restart(): void
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:restart',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);
    }
}
