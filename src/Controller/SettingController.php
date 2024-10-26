<?php

namespace App\Controller;

use App\Clock\Content\GameRound\GameRoundType;
use App\Clock\Content\GameSession\GameSessionService;
use App\Clock\Content\Setting\AlbumDisplayMode;
use App\Clock\Content\Setting\Data\SettingData;
use App\Clock\Content\Setting\SettingService;
use App\Clock\Content\ShutdownSchedule\Data\ShutdownScheduleData;
use App\Clock\Content\ShutdownSchedule\ScheduleListRepository;
use App\Clock\Content\ShutdownSchedule\ShutdownScheduleService;
use App\Clock\LedMatrixDisplayMode;
use App\Clock\LedMatrixDisplayService;
use App\Clock\LyricGameProcessor;
use App\Entity\ScheduleList;
use App\Entity\ShutdownSchedule;
use App\Form\ScheduleListType;
use App\Form\SettingsType;
use App\Form\ShutdownScheduleType;
use App\Form\ToggleDisplayType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
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
                exec('sh ' . $this->scriptPath . ' off');
            } elseif ($form->get('toggleOff')->isClicked()) {
                exec('sh ' . $this->scriptPath . ' on');
            }

            // Optionally add a flash message or redirect
            $this->addFlash('success', 'Display toggled successfully.');

            return $this->redirectToRoute('app_setting_power');
        }

        return $this->render('setting/power.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'app_setting_ui')]
    public function setting(Request $request, SettingService $settingService, LyricGameProcessor $lyricGameProcessor): Response
    {
        $form = $this->createFormBuilder()
            ->add('carousel', SubmitType::class, [
                'label' => 'Carousel',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->add('split', SubmitType::class, [
                'label' => 'Split',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->add('new', SubmitType::class, [
                'label' => 'New',
                'attr' => ['class' => 'btn btn-success'],
            ])
            ->add('clock', SubmitType::class, [
                'label' => 'Clock',
                'attr' => ['class' => 'btn btn-success'],
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $submittedButton = $form->getClickedButton()->getName();

            switch ($submittedButton) {
                case 'carousel':
                    $settingService->setCurrentAlbumDisplayMode(AlbumDisplayMode::CAROUSEL);
                    break;
                case 'split':
                    $settingService->setCurrentAlbumDisplayMode(AlbumDisplayMode::SPLIT);
                    break;
                case 'clock':
                    $settingService->setCurrentAlbumDisplayMode(AlbumDisplayMode::CLOCK);
                    break;
                case 'new':
                    $lyricGameProcessor->setNewRandomLyric();
                    break;
            }

            // Optionally, add a flash message or redirect
            $this->addFlash('success', sprintf('Mode set to %s', $submittedButton));
            return $this->redirectToRoute('app_setting_ui'); // Redirect to the same page
        }

        return $this->render('setting/home.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/handle', name: 'app_setting_ajax')]
    public function handle(Request $request): Response
    {
        $mode = $request->request->get('mode');

        switch ($mode) {
            case 'carousel':
                // Trigger action for Mode1: Carousel
                exec('sh /path/to/your/mode_script.sh carousel');
                break;

            case 'permanent':
                // Trigger action for Mode2: Permanent
                exec('sh /path/to/your/mode_script.sh permanent');
                break;

            case 'new':
                // Trigger action for Mode3: New
                exec('sh /path/to/your/mode_script.sh new');
                break;

            default:
                return new JsonResponse(['status' => 'error', 'message' => 'Invalid mode'], 400);
        }

        return new JsonResponse(['status' => 'success', 'message' => "Mode set to $mode"]);
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
