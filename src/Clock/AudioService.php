<?php

declare(strict_types=1);

namespace App\Clock;

use App\Entity\Audio;
use App\File\FileService;
use App\File\FileType;
use App\File\Flysystem\FilesystemProvider;
use App\Repository\AudioRepository;
use getID3;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class AudioService
{
    public const LOOSE_AUDIO = 'audios_loose_dth_tage';

    public function __construct(
        private readonly AudioRepository $audioRepository,
        private readonly FileService $fileService,
        private KernelInterface $kernel
    ) {
    }

    public function createAudioByMp3File(string $filePath, string $identifier): Audio
    {
        $audio = new Audio();

        $audio->setIdentifier($identifier);

        $fileNameComplete = basename($filePath);
        $parts = explode('.', $fileNameComplete);
        $stream = fopen($filePath, 'r+');

        $file = $this->fileService->importBinaryFileIntoFilesystem(
            $parts[0],
            'mp3',
            FileType::AUDIO,
            FilesystemProvider::IDENT_SONG,
            $stream
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        $getID3 = new getID3;

        $fileInfo = $getID3->analyze($filePath);
        $audio->setPlayTime($fileInfo['playtime_seconds']);

        $audio->setFile($file);

        $this->audioRepository->persist($audio);
        $this->audioRepository->flush();

        return $audio;
    }

    public function playAudio(Audio $audio): void
    {
        try {
            $pythonScriptPath = $this->kernel->getProjectDir().'/play_audio.py';
            $filePath = $this->fileService->getFullQualifiedPath($audio->getFile());
            $process = new Process(['/usr/bin/python3', $pythonScriptPath, $filePath]);
            $process->mustRun(); // This will throw an exception in case of an error
            dump($process->getPid()); // This will throw an exception in case of an error
        }
        catch (ProcessFailedException $exception) {
            echo $exception->getMessage(); // Log this error or handle it in the way it suits you
        }
    }

    public function getAudio(int $id): Audio
    {
        return $this->audioRepository->find($id);
    }

    public function getAudioByIdentifier(string $identifier): Audio
    {
        return $this->audioRepository->findOneBy(['identifier' => $identifier]);
    }

    public function getRandomWinImprovisation(): Audio
    {
        $improvisations = $this->audioRepository->getImprovisationAudios();

        return $improvisations[array_rand($improvisations)];
    }
}
