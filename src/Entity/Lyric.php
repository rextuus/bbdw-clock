<?php

namespace App\Entity;

use App\Discography\Content\Lyric\LyricRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LyricRepository::class)]
class Lyric
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2000)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'lyrics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Song $song = null;

    /**
     * @var Collection<int, GameRound>
     */
    #[ORM\OneToMany(targetEntity: GameRound::class, mappedBy: 'lyric', orphanRemoval: true)]
    private Collection $gameRounds;

    public function __construct()
    {
        $this->gameRounds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
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

    /**
     * @return Collection<int, GameRound>
     */
    public function getGameRounds(): Collection
    {
        return $this->gameRounds;
    }

    public function addGameRound(GameRound $gameRound): static
    {
        if (!$this->gameRounds->contains($gameRound)) {
            $this->gameRounds->add($gameRound);
            $gameRound->setLyric($this);
        }

        return $this;
    }

    public function removeGameRound(GameRound $gameRound): static
    {
        if ($this->gameRounds->removeElement($gameRound)) {
            // set the owning side to null (unless already changed)
            if ($gameRound->getLyric() === $this) {
                $gameRound->setLyric(null);
            }
        }

        return $this;
    }
}
