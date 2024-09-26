<?php

namespace App\Entity;

use App\Discography\Content\Contribution\ContributionRepository;
use App\Discography\Content\Contribution\ContributionType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContributionRepository::class)]
class Contribution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Song $song = null;

    #[ORM\ManyToOne(inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?God $god = null;

    #[ORM\Column(enumType: ContributionType::class)]
    private ?ContributionType $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSong(): ?Song
    {
        return $this->song;
    }

    public function setSong(?Song $song): static
    {
        $this->song = $song;

        return $this;
    }

    public function getGod(): ?God
    {
        return $this->god;
    }

    public function setGod(?God $god): static
    {
        $this->god = $god;

        return $this;
    }

    public function getType(): ?ContributionType
    {
        return $this->type;
    }

    public function setType(ContributionType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
