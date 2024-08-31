<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\PropertyCharacteristics;
use App\DataTransferObject\PropertyLocation;
use App\Factory\PropertyFactory;
use App\Form\PropertyCharacteristicsFormType;
use App\Form\PropertyLocationFormType;
use App\Repository\PropertyRepository;
use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddPropertyController extends AbstractController
{
    private const CREATE_PROPERTY_STEP_ONE = 'location';
    private const CREATE_PROPERTY_STEP_TWO = 'characteristics';

    public function __construct(
        private readonly PropertyRepository $propertyRepository,
        private readonly RequestStack $requestStack,
        private readonly PropertyFactory $propertyFactory
    ) {}

    #[Route('/properties/add/{step}', name: 'add_property')]
    public function add(string $step, Request $request, ImageUploader $imageUploader): Response {
        $form = match ($step) {
             self::CREATE_PROPERTY_STEP_ONE => $this->renderCreatePropertyStepOne(),
             self::CREATE_PROPERTY_STEP_TWO => $this->renderCreatePropertyStepTwo(),
             default => $this->redirectToRoute('add_property', ['step' => self::CREATE_PROPERTY_STEP_ONE])
        };

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            return match(true) {
                $step === self::CREATE_PROPERTY_STEP_ONE => $this->handlePropertyFormStepOne($form),
                $step === self::CREATE_PROPERTY_STEP_TWO => $this->handlePropertyFormStepTwo($form, $imageUploader),
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
        $newProperty = $form->getData();


        $newProperty->setLatitude(0.099766);
        $newProperty->setLongtitude(-2.099766);

        $this->requestStack->getSession()->set('property-form-step-one', $newProperty);

        return $this->redirectToRoute('add_property', ['step' => self::CREATE_PROPERTY_STEP_TWO]);
    }

    private function handlePropertyFormStepTwo(FormInterface $form, ImageUploader $imageUploader): Response {
        /**
         * @var PropertyLocation $propertyLocationDTO
         */
        $propertyLocationDTO = $this->requestStack->getSession()->get('property-form-step-one');

        $newProperty = $form->getData();
        /** @var UploadedFile */
        $imagePath = $form->get('image_path')->getData();
        if ($imagePath) {
            $newFileName = $imageUploader->upload($imagePath);
            $newProperty->setImagePath($newFileName);
        }

        $property = $this->propertyFactory->createFormDtos($propertyLocationDTO, $newProperty);

        $this->propertyRepository->save($property, true);

        $this->requestStack->getSession()->set('property-form-step-one', null);
        $this->requestStack->getSession()->set('property-form-step-two', null);
        
        return $this->redirectToRoute('properties', ['id' => $property->getId()]);
    }
}