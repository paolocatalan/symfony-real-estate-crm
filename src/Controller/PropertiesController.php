<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Property;
use App\Message\Command\SaveInquiry;
use App\Message\ContactAgentNotification;
use App\Service\Property\PropertyValuation;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
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

    #[Route('/properties/{id}', methods: ['GET'], name: 'show_property')]
    public function show($id): Response {
        $property = $this->propertyRepository->find($id);

        $cache = new FilesystemAdapter();

        $value = $cache->get('property_'. $id .'_value', function (ItemInterface $item) use ($property): array {
            $item->expiresAfter(3600);
            return (new PropertyValuation($property))->calculate();
        });

        return $this->render('/property/show.html.twig', [
            'property' => $property,
            'value' => $value
        ]);
    }

    #[Route('/contact', name: 'contact_properties_agent')]
    public function contact(MessageBusInterface $bus): Response
    {
        // $message = new class {
        //     public function  getId(): int {
        //         return 1;
        //     }

        //     public function getUser(): object {
        //         return new class {
        //             public function getEmail(): string {
        //                 return 'paolo_catalan@yahoo.com';
        //             }
        //         };
        //     }
        // };

        $bus->dispatch(new SaveInquiry());

        return $this->render('contact/index.html.twig');
    }

}
