<?php

namespace App\Entity;

use App\File\FileRepository;
use App\File\FileType;
use App\File\ImageType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $extension = null;

    #[ORM\Column(enumType: FileType::class)]
    private ?FileType $type = null;

    #[ORM\Column(nullable: true, enumType: ImageType::class)]
    private ?ImageType $imageType = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    private ?string $filesystemIdent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): static
    {
        $this->extension = $extension;

        return $this;
    }

    public function getType(): ?FileType
    {
        return $this->type;
    }

    public function setType(FileType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getImageType(): ?ImageType
    {
        return $this->imageType;
    }

    public function setImageType(?ImageType $imageType): static
    {
        $this->imageType = $imageType;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getFilesystemIdent(): ?string
    {
        return $this->filesystemIdent;
    }

    public function setFilesystemIdent(string $filesystemIdent): static
    {
        $this->filesystemIdent = $filesystemIdent;

        return $this;
    }

    public function isImage(): bool
    {
        return $this->getType() === FileType::IMAGE;
    }

    public function getRelativePath(): string
    {
        return $this->getPath() . DIRECTORY_SEPARATOR . $this->getName() . '.' . $this->getExtension();
    }
}
