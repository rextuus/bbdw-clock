<?php

namespace App\Entity;

use App\Discography\Content\Song\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Contribution>
     */
    #[ORM\OneToMany(targetEntity: Contribution::class, mappedBy: 'song', orphanRemoval: true)]
    private Collection $contributions;

    /**
     * @var Collection<int, Lyric>
     */
    #[ORM\OneToMany(targetEntity: Lyric::class, mappedBy: 'song', orphanRemoval: true)]
    private Collection $lyrics;

    /**
     * @var Collection<int, Track>
     */
    #[ORM\OneToMany(targetEntity: Track::class, mappedBy: 'song', orphanRemoval: true)]
    private Collection $tracks;

    public function __construct()
    {
        $this->contributions = new ArrayCollection();
        $this->lyrics = new ArrayCollection();
        $this->tracks = new ArrayCollection();
    }

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
            $contribution->setSong($this);
        }

        return $this;
    }

    public function removeContribution(Contribution $contribution): static
    {
        if ($this->contributions->removeElement($contribution)) {
            // set the owning side to null (unless already changed)
            if ($contribution->getSong() === $this) {
                $contribution->setSong(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Lyric>
     */
    public function getLyrics(): Collection
    {
        return $this->lyrics;
    }

    public function addLyric(Lyric $lyric): static
    {
        if (!$this->lyrics->contains($lyric)) {
            $this->lyrics->add($lyric);
            $lyric->setSong($this);
        }

        return $this;
    }

    public function removeLyric(Lyric $lyric): static
    {
        if ($this->lyrics->removeElement($lyric)) {
            // set the owning side to null (unless already changed)
            if ($lyric->getSong() === $this) {
                $lyric->setSong(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): static
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks->add($track);
            $track->setSong($this);
        }

        return $this;
    }

    public function removeTrack(Track $track): static
    {
        if ($this->tracks->removeElement($track)) {
            // set the owning side to null (unless already changed)
            if ($track->getSong() === $this) {
                $track->setSong(null);
            }
        }

        return $this;
    }
}
