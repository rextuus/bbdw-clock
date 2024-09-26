<?php

declare(strict_types=1);

namespace App\File\Flysystem;

use Exception;
use League\Flysystem\FilesystemOperator;

class FilesystemProvider
{
    public const IDENT_ALBUM = 'album';

    public function __construct(
        private readonly FilesystemOperator $albumFilesystem,
    ) {
    }

    /**
     * @throws Exception
     */
    public function getFilesystem(string $identifier): FilesystemOperator
    {
        return match ($identifier) {
            self::IDENT_ALBUM => $this->albumFilesystem,
            default => throw new Exception(
                'Filesystem identifier "' . $identifier . '" is not supported.'
            ),
        };
    }
}
