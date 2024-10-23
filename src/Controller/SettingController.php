<?php

namespace App\Controller;

use App\Clock\Content\GameRound\GameRoundType;
use App\Clock\Content\GameSession\GameSessionService;
use App\Clock\Content\Setting\Data\SettingData;
use App\Clock\Content\Setting\SettingService;
use App\Clock\LedMatrixDisplayMode;
use App\Clock\LedMatrixDisplayService;
use App\Form\SettingsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/setting')]
class SettingController extends AbstractController
{
    #[Route('/edit', name: 'app_setting_edit')]
    public function index(
        Request $request,
        SettingService $settingService,
        GameSessionService $gameSessionService,
        LedMatrixDisplayService $ledMatrixDisplayService
    ): Response
    {
        $setting = $settingService->getSettings();

        $data = (new SettingData())->initFromEntity($setting);
        $form = $this->createForm(SettingsType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SettingData $data */
            $data = $form->getData();
            $settings = $settingService->getSettings();

            $needLedMatrixUpdate = $data->getLedMatrixDisplayMode() != $settings->getLedMatrixMode() ||
                $data->getFontColor() != $settings->getFontColor();

            $settingService->updateDefaultSettings($data);
            $gameSessionService->setForceDisplayUpdate(true);

            if ($needLedMatrixUpdate) {
                $currentDisplayText = $settingService->getCurrentLedText();

                match ($data->getLedMatrixDisplayMode()) {
                    LedMatrixDisplayMode::OFF => throw new \Exception('To be implemented'),
                    LedMatrixDisplayMode::PERMANENT => $ledMatrixDisplayService->displayStaticText($currentDisplayText),
                    LedMatrixDisplayMode::RUNNING => $ledMatrixDisplayService->displayScrollingText($currentDisplayText),
                };
            }

        }

        return $this->render('setting/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
