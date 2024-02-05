<?php

namespace App\Controller;

use App\Entity\Garden;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class APIGardenController extends AbstractController
{
    /**
     * @Route("/api/garden/{id}", name="api_garden_id", methods={"GET"})
     */
    public function showGarden(Garden $garden = null)
    {
        return $this->json(
            $garden,
            Response::HTTP_OK,
            [],
            ['groups' => 'show_garden']
        );
    }
}
