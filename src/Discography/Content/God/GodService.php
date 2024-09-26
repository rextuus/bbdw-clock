<?php

declare(strict_types=1);

namespace App\Discography\Content\God;

use App\Entity\God;

class GodService
{
    public function __construct(readonly private GodRepository $godRepository)
    {
    }

    public function initBestBandInTheWorld(): void
    {
        $rod = new God();
        $rod->setName(GodName::GONZALES);
        $bela = new God();
        $bela->setName(GodName::FELSENHEIMER);
        $farin = new God();
        $farin->setName(GodName::URLAUB);

        $this->godRepository->persist($rod);
        $this->godRepository->persist($bela);
        $this->godRepository->persist($farin);
        $this->godRepository->flush();
    }

    public function findGodByName(GodName $godName): ?God
    {
        return $this->godRepository->findOneBy(['name' => $godName]);
    }

    public function storeNonGod(string $texter): God
    {
        $nonGod = new God();
        $nonGod->setName(GodName::NO_GOD);
        $nonGod->setNoGodName($texter);
        $this->godRepository->persist($nonGod);
        $this->godRepository->flush();

        return $nonGod;
    }

    public function findNonGodByName(string $name): ?God
    {
        return $this->godRepository->findOneBy(['noGodName' => $name]);
    }
}
