<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Entity\Song as Song;

#[AsLiveComponent]
final class Albums
{
    use DefaultActionTrait;

    public Song $song;

    /**
     * @return array<string>
     */
    public function getAlbums(): array
    {
        $infos = [];
        foreach ($this->song->getTracks() as $track){
            $infos[] = sprintf(
                '<div class="p-3 mb-1 bg-light text-dark">
                            <span class="text-primary">#%d</span> auf 
                            <span class="badge bg-info text-dark">%s</span>
                            <span class="badge bg-primary text-dark">%s (%s)</span>
                        </div>',
                $track->getNumber(),
                $track->getAlbum()->getType()->name,
                $track->getAlbum()->getName(),
                $track->getAlbum()->getYear(),
            );

        }

        return $infos;
    }
}
