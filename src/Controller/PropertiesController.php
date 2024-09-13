<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Property;
use App\Form\ContactAgentFormType;
use App\Message\ContactAgentNotification;
use App\Service\Property\PropertyValuation;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PropertiesController extends AbstractController
{
    public function __construct(
        private readonly PropertyRepository $propertyRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    #[Route('/properties', name: 'properties')]
    public function index(): Response {
        $repository = $this->entityManager->getRepository(Property::class);
        $properties = $repository->findAll();
        return $this->render('/property/index.html.twig', [
            'properties' => $properties
        ]);
    }

    #[Route('/properties/{id}', methods: ['GET', 'POST'], name: 'show_property')]
    public function show($id, Request $request, MessageBusInterface $bus): Response {
        $property = $this->propertyRepository->find($id);

        $form = $this->createForm(ContactAgentFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $this->entityManager->persist($formData);
            $this->entityManager->flush();

            $bus->dispatch(new ContactAgentNotification($formData->getName()));

            return $this->redirectToRoute('contact_agent');
        }

        return $this->render('/property/show.html.twig', [
            'property' => $property,
            'form' => $form
        ]);
    }

    #[Route('/market-insights/{id}', name: 'market_insights')]
    public function fetch($id, Request $request): JsonResponse {
        if (!$request->headers->has('HX-request')) {
            return $this->json(['error' => 'Unauthorized.'], 403);
        }

        $property = $this->propertyRepository->find($id);
        $cache = new FilesystemAdapter();

        $value = $cache->get('property_'. $id .'_value', function (ItemInterface $item) use ($property): array {
            $item->expiresAfter(3600);
            return (new PropertyValuation($property))->calculate();
        });

        return $this->json($value);
    }

    #[Route('/contact', name: 'contact_agent')]
    public function contact(MessageBusInterface $bus): Response {
        // Serialization of 'class@anonymous' is not allowed
        // $notification = new class {
        //     public function getId(): int {
        //         return 37;
        //     }

        //     public function getAgent(): object {
        //         return new class {
        //             public function getEmail(): string {
        //                 return 'paolo_catalan@yahoo.com';
        //             }
        //         };
        //     }
        // };

        // $bus->dispatch(new ContactAgentNotification($notification->getId()));

        return $this->render('contact/index.html.twig');
    }

}
