<?php

namespace App\Controller;

use App\Dto\SearchRequestDto;
use App\Entity\User;
use App\Repository\ElasticaRepository;
use App\SearchDecorator\SearchDecorator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/api/search', name: 'app_search', methods: ['GET'])]
class SearchController extends AbstractController
{
    public function __construct(
        private readonly ElasticaRepository $elasticaRepository,
        private readonly Security $security,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route(path: '/stations', name: 'stations')]
    public function searchStations(
        #[CurrentUser()] User $user,
        #[MapQueryString] SearchRequestDto $searchRequest,
        Request $request,
    ): JsonResponse {
        $parameters = $request->query->all();
        $search = new SearchDecorator($parameters);

        $response = $this->elasticaRepository->search(
            $user,
            $search->getSearch(),
            $searchRequest->page,
            $searchRequest->itemsPerPage
        );

        $normalizedResponse = $this->normalizer->normalize($response, null, ['groups' => ['station:read']]);

        return new JsonResponse($normalizedResponse, JsonResponse::HTTP_OK);
    }
}
