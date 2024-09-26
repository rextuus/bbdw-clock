<?php

namespace App\Twig\Components;

use App\Discography\Content\Contribution\ContributionType;
use App\Entity\God;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Song
{
    public \App\Entity\Song $song;

    /**
     * @return array<God>
     */
    public function getTexter(): array
    {
        $texter = [];
        foreach ($this->song->getContributions() as $contribution) {
            if ($contribution->getType() === ContributionType::TEXT){
                $texter[] = $contribution->getGod();
            }
        }

        return $texter;
    }

    /**
     * @return array<God>
     */
    public function getComposers(): array
    {
        $composers = [];
        foreach ($this->song->getContributions() as $contribution) {
            if ($contribution->getType() === ContributionType::MUSIC){
                $composers[] = $contribution->getGod();
            }
        }

        return $composers;
    }
}
