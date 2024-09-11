<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\PropertyCharacteristics;
use App\DataTransferObject\PropertyLocation;
use App\Entity\User;
use App\Factory\PropertyFactory;
use App\Form\PropertyCharacteristicsFormType;
use App\Form\PropertyFormType;
use App\Form\PropertyLocationFormType;
use App\Service\GeoCoding\GeoCodingInterface;
use App\Repository\PropertyRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ManagePropertiesController extends AbstractController
{
    private const CREATE_PROPERTY_STEP_ONE = 'location';
    private const CREATE_PROPERTY_STEP_TWO = 'characteristics';

    public function __construct(
        private readonly PropertyRepository $propertyRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestStack $requestStack,
        private readonly PropertyFactory $propertyFactory,
        private readonly GeoCodingInterface $geoApify
    ) {}

    #[Route('/properties/add/{step}', name: 'add_property')]
    public function add(string $step, Request $request, #[CurrentUser] User $currentUser, ImageUploader $imageUploader): Response {
        $form = match ($step) {
             self::CREATE_PROPERTY_STEP_ONE => $this->renderCreatePropertyStepOne(),
             self::CREATE_PROPERTY_STEP_TWO => $this->renderCreatePropertyStepTwo(),
             default => $this->redirectToRoute('add_property', ['step' => self::CREATE_PROPERTY_STEP_ONE])
        };

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return match(true) {
                $step === self::CREATE_PROPERTY_STEP_ONE => $this->handlePropertyFormStepOne($form),
                $step === self::CREATE_PROPERTY_STEP_TWO => $this->handlePropertyFormStepTwo($currentUser, $form, $imageUploader),
                default => $this->redirectToRoute('add_property', ['step' => self::CREATE_PROPERTY_STEP_ONE])
            };
        }

        return $this->render(sprintf('property/create/step-%s.html.twig', $step), [
            'form' => $form,
            'data' => $form->getData()
        ]);
    }

    private function renderCreatePropertyStepOne(): FormInterface {
        $propertyLocationDTO = $this->requestStack->getSession()->get('property-form-step-one');

        if (!$propertyLocationDTO instanceof PropertyLocation) {
            $propertyLocationDTO = new PropertyLocation();
        }

        return $this->createForm(PropertyLocationFormType::class, $propertyLocationDTO);
    }

    private function renderCreatePropertyStepTwo(): FormInterface {
        $propertyCharacteristicsDTO = $this->requestStack->getSession()->get('property-form-step-two');

        if (!$propertyCharacteristicsDTO instanceof PropertyCharacteristics) {
            $propertyCharacteristicsDTO = new PropertyCharacteristics();
        }

        return $this->createForm(PropertyCharacteristicsFormType::class, $propertyCharacteristicsDTO);
    }

    private function handlePropertyFormStepOne(FormInterface $form): Response {
        try {
            $geoCodeResponse = $this->geoApify->lookup(
                $form->get('address')->getData(),
                $form->get('city')->getData(),
                $form->get('state')->getData(),
                $form->get('zip_code')->getData(),
                $form->get('country')->getData()
            );
        } catch (\Throwable $e) {
            // $logger->error($e->getMessage());
            $this->addFlash('message', $e->getMessage() . '. Please try again later.');
            return $this->redirectToRoute('properties');
        }

        if ($geoCodeResponse->confidence !== null && $geoCodeResponse->confidence < 0.94) {
            if ($geoCodeResponse->confidenceStreetLevel >= 0.95) {
                $this->addFlash('message', 'House number not found.');
            } else if ($geoCodeResponse->confidenceCityLevel >= 0.95) {
                $this->addFlash('message', 'Street level doubts.');
            } else {
                $this->addFlash('message', 'City level doubts.');
            }
            return $this->redirectToRoute('add_property', ['step' => self::CREATE_PROPERTY_STEP_ONE]);
        }

        // Google returns OK in special characters address, returning null
        // if ($geoCodeResponse->status !== "OK") {
        //     match ($geoCodeResponse->status) {
        //         'ZERO_RESULTS' => $this->addFlash('message', 'No results found.'),
        //         'INVALID_REQUEST' => $this->addFlash('message', 'Invalid address.'),
        //         'UNKNOWN_ERROR' => $this->addFlash('message', 'Something unexpected happened, please try again.'),
        //         default => $this->addFlash('message', 'Something went wrong, please try again later.'),
        //     };
        //     return $this->redirectToRoute('add_property', ['step' => self::CREATE_PROPERTY_STEP_ONE]);
        // }

        $propertyData = $form->getData();
        $propertyData->setLatitude($geoCodeResponse->latitude);
        $propertyData->setLongtitude($geoCodeResponse->longtitude);

        $this->requestStack->getSession()->set('property-form-step-one', $propertyData);
        return $this->redirectToRoute('add_property', ['step' => self::CREATE_PROPERTY_STEP_TWO]);
    }

    private function handlePropertyFormStepTwo(User $user, FormInterface $form, ImageUploader $imageUploader): Response {
        /**
         * @var PropertyLocation $propertyLocationDTO
         */
        $propertyLocationDTO = $this->requestStack->getSession()->get('property-form-step-one');

        $propertyData = $form->getData();
        /** @var UploadedFile */
        $imagePath = $form->get('image_path')->getData();
        if ($imagePath) {
            $newFileName = $imageUploader->upload($imagePath);
            $propertyData->setImagePath($newFileName);
        }

        $property = $this->propertyFactory->createFormDtos($user, $propertyLocationDTO, $propertyData);

        $this->propertyRepository->save($property, true);

        $this->requestStack->getSession()->set('property-form-step-one', null);
        $this->requestStack->getSession()->set('property-form-step-two', null);

        $this->addFlash('message', 'Property added successfully.');

        return $this->redirectToRoute('properties');
    }

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

    #[Route('/properties/{id}/delete', methods: ['GET', 'DELETE'], name: 'delete_property')]
    public function delete($id): Response {
        $property = $this->propertyRepository->find($id);

        $this->entityManager->remove($property);
        $this->entityManager->flush();

        $this->addFlash('message', 'Property deleted successfully.');

        return $this->redirectToRoute('properties');
    }
}