<?php

namespace App\Entity;

use App\Discography\Content\Album\AlbumRepository;
use App\Discography\Content\Album\ReleaseType;
use App\File\FileRelationTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    use FileRelationTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column(enumType: ReleaseType::class)]
    private ?ReleaseType $type = null;

    #[ORM\Column(nullable: true)]
    private ?int $amountOfTracks = null;

    /**
     * @var Collection<int, Track>
     */
    #[ORM\OneToMany(targetEntity: Track::class, mappedBy: 'album', orphanRemoval: true)]
    private Collection $tracks;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $rawTrackData = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverImageUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $detailUrl = null;

    public function __construct()
    {
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getType(): ?ReleaseType
    {
        return $this->type;
    }

    public function setType(ReleaseType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAmountOfTracks(): ?int
    {
        return $this->amountOfTracks;
    }

    public function setAmountOfTracks(?int $amountOfTracks): static
    {
        $this->amountOfTracks = $amountOfTracks;

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
            $track->setAlbum($this);
        }

        return $this;
    }

    public function removeTrack(Track $track): static
    {
        if ($this->tracks->removeElement($track)) {
            // set the owning side to null (unless already changed)
            if ($track->getAlbum() === $this) {
                $track->setAlbum(null);
            }
        }

        return $this;
    }

    public function getRawTrackData(): ?array
    {
        return $this->rawTrackData;
    }

    public function setRawTrackData(?array $rawTrackData): static
    {
        $this->rawTrackData = $rawTrackData;

        return $this;
    }

    public function getCoverImageUrl(): ?string
    {
        return $this->coverImageUrl;
    }

    public function setCoverImageUrl(?string $coverImageUrl): static
    {
        $this->coverImageUrl = $coverImageUrl;

        return $this;
    }

    public function getDetailUrl(): ?string
    {
        return $this->detailUrl;
    }

    public function setDetailUrl(string $detailUrl): static
    {
        $this->detailUrl = $detailUrl;

        return $this;
    }
}
