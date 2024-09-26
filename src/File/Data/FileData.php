<?php

declare(strict_types=1);

namespace App\File\Data;

use App\File\FileType;
use App\File\ImageType;

class FileData
{
    private string $name;

    private string $extension;

    private FileType $type;

    private ?ImageType $imageType = null;

    private string $path;

    private string $filesystemIdent;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): FileData
    {
        $this->name = $name;
        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): FileData
    {
        $this->extension = $extension;
        return $this;
    }

    public function getType(): FileType
    {
        return $this->type;
    }

    public function setType(FileType $type): FileData
    {
        $this->type = $type;
        return $this;
    }

    public function getImageType(): ?ImageType
    {
        return $this->imageType;
    }

    public function setImageType(?ImageType $imageType): FileData
    {
        $this->imageType = $imageType;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): FileData
    {
        $this->path = $path;
        return $this;
    }

    public function getFilesystemIdent(): string
    {
        return $this->filesystemIdent;
    }

    public function setFilesystemIdent(string $filesystemIdent): FileData
    {
        $this->filesystemIdent = $filesystemIdent;
        return $this;
    }
}
