<?php

namespace App\Controller\Search;

use App\Dto\SearchRequestDto;
use App\Entity\User;
use App\Repository\ElasticaStationRepository;
use App\SearchDecorator\SearchDecorator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/api/search', name: 'app_search', methods: ['GET'])]
class SearchController extends AbstractController
{
    public function __construct(
        private readonly ElasticaStationRepository $elasticaStationRepository,
        private readonly Security $security,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route(path: '/stations', name: 'stations')]
    public function searchStations(
        #[MapQueryString] SearchRequestDto $searchRequest,
        Request $request,
    ): JsonResponse {
        $parameters = $request->query->all();
        $search = new SearchDecorator($parameters);

        $response = $this->elasticaStationRepository->search(
            $search->getSearch(),
            $searchRequest->page,
            $searchRequest->itemsPerPage
        );

        $normalizedResponse = $this->normalizer->normalize($response, null, ['groups' => ['station:search']]);
        return new JsonResponse($normalizedResponse, JsonResponse::HTTP_OK);
    }
}
