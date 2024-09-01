<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\PropertyCharacteristics;
use App\DataTransferObject\PropertyLocation;
use App\Entity\User;
use App\Factory\PropertyFactory;
use App\Form\PropertyCharacteristicsFormType;
use App\Form\PropertyLocationFormType;
use App\Repository\PropertyRepository;
use App\Service\GeoCoding;
use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

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
    public function add(string $step, Request $request, #[CurrentUser] User $currentUser, ImageUploader $imageUploader, GeoCoding $geoCoding): Response {
        $form = match ($step) {
             self::CREATE_PROPERTY_STEP_ONE => $this->renderCreatePropertyStepOne(),
             self::CREATE_PROPERTY_STEP_TWO => $this->renderCreatePropertyStepTwo(),
             default => $this->redirectToRoute('add_property', ['step' => self::CREATE_PROPERTY_STEP_ONE])
        };

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            return match(true) {
                $step === self::CREATE_PROPERTY_STEP_ONE => $this->handlePropertyFormStepOne($form, $geoCoding),
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

    private function handlePropertyFormStepOne(FormInterface $form, GeoCoding $geoCoding): Response {
        $geoCodeResponse = $geoCoding->lookup(
            $form->get('address')->getData(),
            $form->get('city')->getData(),
            $form->get('state')->getData(),
            $form->get('zip_code')->getData(),
            $form->get('country')->getData()
        );

        if ($geoCodeResponse['features'][0]['properties']['rank']['confidence'] >= 0.95) {
            $propertyData = $form->getData();
            $propertyData->setLatitude($geoCodeResponse['features'][0]['properties']['lat']);
            $propertyData->setLongtitude($geoCodeResponse['features'][0]['properties']['lon']);

            $this->requestStack->getSession()->set('property-form-step-one', $propertyData);
            return $this->redirectToRoute('add_property', ['step' => self::CREATE_PROPERTY_STEP_TWO]);
        } else if ($geoCodeResponse['features'][0]['properties']['rank']['confidence'] < 0.2) {
            $this->addFlash('message', 'Address can not confirm.');
        } else {
            if (isset($geoCodeResponse['features'][0]['properties']['rank']['confidence_street_level']) && $geoCodeResponse['features'][0]['properties']['rank']['confidence_street_level'] >= 0.95 ) {
                $this->addFlash('message', 'House number not found.');
            } else if (isset($geoCodeResponse['features'][0]['properties']['rank']['confidence_city_level']) && $geoCodeResponse['features'][0]['properties']['rank']['confidence_city_level'] >= 0.95) {
                $this->addFlash('message', 'Street level in doubts.');
            } else {
                $this->addFlash('message', 'City level in doubts.');
            }
        }
        return $this->redirectToRoute('add_property', ['step' => self::CREATE_PROPERTY_STEP_ONE]);
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

        return $this->redirectToRoute('properties');
    }
}