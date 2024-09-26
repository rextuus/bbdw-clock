<?php

declare(strict_types=1);

namespace App\Clock;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class AlbumDisplayService
{
    public function __construct(private KernelInterface $kernel)
    {
    }

    public function displayDisplayOnTctMonitorViaPythonLibrary(): void
    {
        // Replace the following path with the path to your Python script
        $pythonScriptPath = $this->kernel->getProjectDir().'/display-album-image.py';

        // The command to run Python and your script
        $command = ['python3', $pythonScriptPath];

        $process = new Process($command);
        try {
            $process->mustRun();
            echo $process->getOutput();
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
    }
}
