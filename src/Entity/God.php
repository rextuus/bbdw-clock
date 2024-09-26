<?php

namespace App\Entity;

use App\Discography\Content\God\GodName;
use App\Discography\Content\God\GodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GodRepository::class)]
class God
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: GodName::class)]
    private ?GodName $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $noGodName = null;

    /**
     * @var Collection<int, Contribution>
     */
    #[ORM\OneToMany(targetEntity: Contribution::class, mappedBy: 'god', orphanRemoval: true)]
    private Collection $contributions;

    public function __construct()
    {
        $this->contributions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?GodName
    {
        return $this->name;
    }

    public function setName(GodName $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getNoGodName(): ?string
    {
        return $this->noGodName;
    }

    public function setNoGodName(?string $noGodName): static
    {
        $this->noGodName = $noGodName;

        return $this;
    }

    /**
     * @return Collection<int, Contribution>
     */
    public function getContributions(): Collection
    {
        return $this->contributions;
    }

    public function addContribution(Contribution $contribution): static
    {
        if (!$this->contributions->contains($contribution)) {
            $this->contributions->add($contribution);
            $contribution->setGod($this);
        }

        return $this;
    }

    public function removeContribution(Contribution $contribution): static
    {
        if ($this->contributions->removeElement($contribution)) {
            // set the owning side to null (unless already changed)
            if ($contribution->getGod() === $this) {
                $contribution->setGod(null);
            }
        }

        return $this;
    }
}
