<?php

declare(strict_types=1);

namespace App\File\Flysystem;

use Exception;
use League\Flysystem\FilesystemOperator;

class FilesystemProvider
{
    public const IDENT_ALBUM = 'album';
    public const IDENT_SONG = 'song';

    public function __construct(
        private readonly FilesystemOperator $albumFilesystem,
        private readonly FilesystemOperator $songFilesystem,
    ) {
    }

    /**
     * @throws Exception
     */
    public function getFilesystem(string $identifier): FilesystemOperator
    {
        return match ($identifier) {
            self::IDENT_ALBUM => $this->albumFilesystem,
            self::IDENT_SONG => $this->songFilesystem,
            default => throw new Exception(
                'Filesystem identifier "' . $identifier . '" is not supported.'
            ),
        };
    }
}
