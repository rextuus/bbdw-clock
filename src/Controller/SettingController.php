<?php

namespace App\Controller;

use App\Clock\Content\GameSession\GameSessionService;
use App\Clock\Content\Setting\AlbumDisplayMode;
use App\Clock\Content\Setting\Data\SettingData;
use App\Clock\Content\Setting\SettingService;
use App\Clock\Content\ShutdownSchedule\ShutdownScheduleRepository;
use App\Clock\Content\ShutdownSchedule\ShutdownScheduleService;
use App\Clock\LedMatrixDisplayMode;
use App\Clock\LedMatrixDisplayService;
use App\Clock\LyricGameProcessor;
use App\Clock\PowerManagementService;
use App\Entity\ShutdownSchedule;
use App\Form\FreeMatrixTextType;
use App\Form\SettingsType;
use App\Form\ShutdownScheduleType;
use App\Form\ToggleDisplayType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/setting')]
class SettingController extends AbstractController
{
    public function __construct(
        private readonly PowerManagementService $powerManagementService,
        private readonly SettingService $settingService,
        private readonly LedMatrixDisplayService $ledMatrixDisplayService
    ) {
    }

    #[Route('/edit', name: 'app_setting_edit')]
    public function index(
        Request $request,
        GameSessionService $gameSessionService,
    ): Response {
        $setting = $this->settingService->getSettings();

        $data = (new SettingData())->initFromEntity($setting);
        $form = $this->createForm(SettingsType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SettingData $data */
            $data = $form->getData();
            $settings = $this->settingService->getSettings();

            $needLedMatrixUpdate = $data->getLedMatrixDisplayMode() != $settings->getLedMatrixMode() ||
                $data->getFontColor() != $settings->getFontColor();

            $this->settingService->updateDefaultSettings($data);
            $gameSessionService->setForceDisplayUpdate(true);

            if ($needLedMatrixUpdate) {
                $currentDisplayText = $this->settingService->getCurrentLedText();

                match ($data->getLedMatrixDisplayMode()) {
                    LedMatrixDisplayMode::OFF => throw new \Exception('To be implemented'),
                    LedMatrixDisplayMode::PERMANENT => $this->ledMatrixDisplayService->displayStaticText(
                        $currentDisplayText
                    ),
                    LedMatrixDisplayMode::RUNNING => $this->ledMatrixDisplayService->displayScrollingText(
                        $currentDisplayText
                    ),
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
                $this->powerManagementService->turnDisplayOn();
            } elseif ($form->get('toggleOff')->isClicked()) {
                $this->powerManagementService->turnDisplayOff();
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
    public function setting(
        Request $request,
        SettingService $settingService,
        LyricGameProcessor $lyricGameProcessor
    ): Response {
        $settings = $this->settingService->getSettings();

        $isCarouselMode = $settings->getAlbumDisplayMode() == AlbumDisplayMode::CAROUSEL;
        $carouselAttributes = [
            'class' => $isCarouselMode ? 'btn btn-secondary' : 'btn btn-primary',
            'disabled' => $isCarouselMode
        ];

        $isSplitMode = $settings->getAlbumDisplayMode() == AlbumDisplayMode::SPLIT;
        $splitAttributes = [
            'class' => $isSplitMode ? 'btn btn-secondary' : 'btn btn-primary',
            'disabled' => $isSplitMode
        ];

        $isClockMode = $settings->getAlbumDisplayMode() == AlbumDisplayMode::CLOCK;
        $clockAttributes = [
            'class' => $isClockMode ? 'btn btn-secondary' : 'btn btn-primary',
            'disabled' => $isClockMode
        ];

        $form = $this->createFormBuilder()
            ->add('carousel', SubmitType::class, [
                'label' => 'Carousel',
                'attr' => $carouselAttributes,
            ])
            ->add('split', SubmitType::class, [
                'label' => 'Split',
                'attr' => $splitAttributes,
            ])
            ->add('new', SubmitType::class, [
                'label' => 'New',
                'attr' => ['class' => 'btn btn-success'],
            ])
            ->add('clock', SubmitType::class, [
                'label' => 'Clock',
                'attr' => $clockAttributes,
            ])
            ->add('text', SubmitType::class, [
                'label' => 'Clock',
                'attr' => [],
            ])
            ->add('power', SubmitType::class, [
                'label' => 'Clock',
                'attr' => [],
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
                case 'text':
                    return $this->redirectToRoute('app_setting_text');
                    break;
                case 'power':
                    return $this->redirectToRoute('app_setting_power');
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

    #[\Symfony\Component\Routing\Annotation\Route('/schedules', name: 'shutdown_schedule_index', methods: [
        'GET',
        'POST'
    ])]
    public function schedules(
        Request $request,
        ShutdownScheduleRepository $repository,
        EntityManagerInterface $entityManager,
        ShutdownScheduleService $shutdownScheduleService,
    ): Response {
        $shutdownScheduleService->getScheduleList();

        $shutdownSchedule = new ShutdownSchedule();
        $form = $this->createForm(ShutdownScheduleType::class, $shutdownSchedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($shutdownSchedule);
            $entityManager->flush();
            return $this->redirectToRoute('shutdown_schedule_index');
        }

        $shutdownSchedules = $repository->findAll();

        return $this->render('setting/schedule.html.twig', [
            'shutdownSchedules' => $shutdownSchedules,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/shutdown_schedule/delete/{id}', name: 'shutdown_schedule_delete', methods: ['POST'])]
    public function delete(ShutdownSchedule $shutdownSchedule, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($shutdownSchedule);
        $entityManager->flush();

        return $this->redirectToRoute('shutdown_schedule_index');
    }

    #[Route('/text', name: 'app_setting_text')]
    public function addText(Request $request): Response
    {
        $form = $this->createForm(FreeMatrixTextType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $currentDisplayText = $data['text'];
            match ($data['mode']) {
                LedMatrixDisplayMode::OFF => throw new \Exception('To be implemented'),
                LedMatrixDisplayMode::PERMANENT => $this->ledMatrixDisplayService->displayStaticText(
                    $currentDisplayText
                ),
                LedMatrixDisplayMode::RUNNING => $this->ledMatrixDisplayService->displayScrollingText(
                    $currentDisplayText
                ),
            };


            return $this->redirectToRoute('app_setting_ui'); // Redirect to clear the form
        }

        return $this->render('setting/matrix_text.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
