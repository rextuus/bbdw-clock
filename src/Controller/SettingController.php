<?php

namespace App\Controller;

use App\Clock\Content\GameRound\GameRoundType;
use App\Clock\Content\GameSession\GameSessionService;
use App\Clock\Content\Setting\Data\SettingData;
use App\Clock\Content\Setting\SettingService;
use App\Clock\Content\ShutdownSchedule\Data\ShutdownScheduleData;
use App\Clock\Content\ShutdownSchedule\ScheduleListRepository;
use App\Clock\Content\ShutdownSchedule\ShutdownScheduleService;
use App\Clock\LedMatrixDisplayMode;
use App\Clock\LedMatrixDisplayService;
use App\Entity\ScheduleList;
use App\Entity\ShutdownSchedule;
use App\Form\ScheduleListType;
use App\Form\SettingsType;
use App\Form\ShutdownScheduleType;
use App\Form\ToggleDisplayType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/setting')]
class SettingController extends AbstractController
{
    public function __construct(#[Autowire('%env(POWER_SWITCH_SCRIP_PATH)%')] private readonly string $scriptPath)
    {
    }

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

    #[Route('/power', name: 'app_setting_power')]
    public function power(Request $request): Response
    {
        $form = $this->createForm(ToggleDisplayType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('toggleOn')->isClicked()) {
                exec('sh ' . $this->scriptPath . ' on');
            } elseif ($form->get('toggleOff')->isClicked()) {
                exec('sh ' . $this->scriptPath . ' off');
            }

            // Optionally add a flash message or redirect
            $this->addFlash('success', 'Display toggled successfully.');

            return $this->redirectToRoute('app_setting_power');
        }

        return $this->render('setting/power.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/schedule', name: 'app_schedule')]
    public function schedule(Request $request, ShutdownScheduleService $scheduleService): Response
    {
        $scheduleList = $scheduleService->getScheduleList();

        return $this->render('setting/schedule.html.twig', [
            'scheduleList' => $scheduleList,
        ]);
    }

}
