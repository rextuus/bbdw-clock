<?php

declare(strict_types=1);

namespace App\File;

class FileGenerationResult
{
    public function __construct(
        private readonly string $fullFilePath,
        private readonly string $directoryPath,
        private readonly string $fileName,
        private readonly string $filesystemIdent
    ) {
    }

    public static function create(
        string $fullFilePath,
        string $directoryPath,
        string $fileName,
        string $filesystemIdent
    ): self {
        return new self($fullFilePath, $directoryPath, $fileName, $filesystemIdent);
    }

    public function getFullFilePath(): string
    {
        return $this->fullFilePath;
    }

    public function getDirectoryPath(): string
    {
        return $this->directoryPath;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFilesystemIdent(): string
    {
        return $this->filesystemIdent;
    }
}
