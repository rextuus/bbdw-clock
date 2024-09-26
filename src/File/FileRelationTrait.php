<?php

namespace App\File;

use App\Entity\File;
use Doctrine\ORM\Mapping as ORM;

trait FileRelationTrait
{
    #[ORM\OneToOne(targetEntity: File::class)]
    #[ORM\JoinColumn(name: 'file_id', referencedColumnName: 'id')]
    private File|null $pdfFile = null;

    public function getFile(): ?File
    {
        return $this->pdfFile;
    }

    public function setFile(?File $pdfFile): self
    {
        $this->pdfFile = $pdfFile;

        return $this;
    }
}
