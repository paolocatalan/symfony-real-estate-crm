<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Property;
use App\Form\ContactAgentFormType;
use App\Form\PropertyFormType;
use App\Message\ContactAgentNotification;
use App\Repository\PropertyRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Messenger\MessageBusInterface;

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

    /**
     * @param mixed $id
     */
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

    /**
     * @param mixed $id
     */
    #[Route('/properties/{id}/edit', name: 'edit_property')]
    public function edit($id, Request $request, ImageUploader $imageUploader): Response {
        $property = $this->propertyRepository->find($id);
        $form = $this->createForm(PropertyFormType::class, $property);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newProperty = $form->getData();

            /** @var UploadedFile */
            $imagePath = $form->get('image_path')->getData();
            if ($imagePath) {
                $newFileName = $imageUploader->upload($imagePath);
                $newProperty->setImagePath($newFileName);
            }

            $this->entityManager->persist($newProperty);
            $this->entityManager->flush();

            $this->addFlash('message', 'Property updated successfully.');

            return $this->redirectToRoute('properties');
        }

        return $this->render('/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param mixed $id
     */
    #[Route('/properties/{id}/delete', methods: ['GET', 'DELETE'], name: 'delete_property')]
    public function destroy($id): Response {
        $property = $this->propertyRepository->find($id);

        $this->entityManager->remove($property);
        $this->entityManager->flush();

        $this->addFlash('message', 'Property deleted successfully.');

        return $this->redirectToRoute('properties');
    }

}
