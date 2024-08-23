<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyFormType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ImageUploader;

class PropertiesController extends AbstractController
{
    private $entityManager;
    private $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository, EntityManagerInterface $entityManager)
    {
        $this->propertyRepository = $propertyRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/properties', name: 'properties')]
    public function index(): Response
    {
        $repository = $this->entityManager->getRepository(Property::class);
        $properties = $repository->findAll();

        return $this->render('index.html.twig', [
            'properties' => $properties
        ]);
    }

    #[Route('/properties/create', name: 'create_properties')]
    public function create(Request $request, ImageUploader $imageUploader): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyFormType::class, $property);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newProperty = $form->getData();

            /** @var UploadedFile $brochureFile */
            $imagePath = $form->get('image_path')->getData();
            if ($imagePath) {
                $newFileName = $imageUploader->upload($imagePath);
                // $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                // try {
                //     $imagePath->move($brochuresDirectory, $newFileName);
                // } catch (FileException $e) {
                //     return new Response($e->getMessage());
                // }

                $newProperty->setImagePath($newFileName);
            }
            $this->entityManager->persist($newProperty);
            $this->entityManager->flush();

            $this->addFlash('message', 'Property instered successfully.');

            return $this->redirectToRoute('properties');
        }

        return $this->render('edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/properties/{id}', methods: ['GET'], name: 'show_property')]
    public function show($id): Response
    {
        $property = $this->propertyRepository->find($id);

        return $this->render('show.html.twig', [
            'property' => $property
        ]);
    }

    #[Route('/properties/{id}/edit', name: 'edit_property')]
    public function edit($id, Request $request, ImageUploader $imageUploader): Response
    {
        $property = $this->propertyRepository->find($id);
        $form = $this->createForm(PropertyFormType::class, $property);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newProperty = $form->getData();

            /** @var UploadedFile $brochureFile */
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

        return $this->render('edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    #[Route('/properties/{id}/delete', methods: ['GET', 'DELETE'], name: 'delete_property')]
    public function delete($id): Response
    {
        $property = $this->propertyRepository->find($id);
        
        $this->entityManager->remove($property);
        $this->entityManager->flush();

        $this->addFlash('message', 'Property deleted successfully.');

        return $this->redirectToRoute('properties');
    }

}
