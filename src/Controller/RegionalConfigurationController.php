<?php declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\RegionalConfiguration;
use App\Form\RegionalConfigurationFilter;
use App\Service\RegionalConfiguration\RegionalConfigurationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegionalConfigurationController extends AbstractController
{
    public function __construct(
        private readonly RegionalConfigurationService $regionalConfigurationService,
        private readonly RequestStack $requestStack
    ) {}

    #[Route('/regional-settings', name: 'regional_settings')]
    public function configure(Request $request): Response {
        $regionalConfiguration = new RegionalConfiguration();
        $regionalConfigurationFilter = $this->createForm(RegionalConfigurationFilter::class, $regionalConfiguration);
        
        $regionalConfigurationFilter->handleRequest($request);
        if ($regionalConfigurationFilter->isSubmitted() && $regionalConfigurationFilter->isValid()) {
            $this->regionalConfigurationService->configure($this->requestStack, $regionalConfiguration);

            return $this->redirectToRoute('regional_settings');
        }

        return $this->render('regional/configuration.html.twig', [
            'regionalConfigurationFilter' => $regionalConfigurationFilter
        ]);
    }
}
