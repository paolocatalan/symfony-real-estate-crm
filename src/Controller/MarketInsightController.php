<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PropertyRepository;
use App\Service\Property\PropertyValuation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\ItemInterface;

class MarketInsightController extends AbstractController
{

    public function __construct(
        private readonly PropertyRepository $propertyRepository,
    ) {}

    /**
     * @param mixed $id
     */
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


}
