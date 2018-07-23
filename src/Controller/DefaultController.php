<?php

namespace App\Controller;

use App\Service\Airports;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return new JsonResponse(["ok" => true, "message" => "Try /airports"]);
    }

    /**
     * @Route("/airports")
     */
    public function airports(Airports $airports)
    {
        $airports = $airports->airports();

        if (!is_array($airports) || count($airports) === 0) {
            throw $this->createNotFoundException('No airports found.');
        }

        return new JsonResponse($airports);
    }

    /**
     * @Route("/airport/{code}")
     */
    public function airport(Airports $airports, string $code)
    {
        $airport = $airports->airport($code);

        if (!is_array($airport)) {
            throw $this->createNotFoundException("No airport found with code $code.");
        }

        return new JsonResponse($airport);
    }

    /**
     * @Route("/cities")
     */
    public function cities(Airports $airports)
    {
        return new JsonResponse($airports->cities());
    }

    /**
     * @Route("/city/{code}")
     */
    public function city(Airports $airports, string $code)
    {
        return new JsonResponse($airports->city($code));
    }
}
