<?php

namespace App\Controller;

use App\Entity\Plant;
use App\Entity\Garden;
use DateTimeImmutable;
use App\Repository\GenreRepository;
use App\Repository\GardenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class APIPlantController extends AbstractController
{
    /**
     * @Route("/a/p/i/plant", name="app_a_p_i_plant")
     */
    public function index(): Response
    {
        return $this->render('api_plant/index.html.twig', [
            'controller_name' => 'APIPlantController',
        ]);
    }

    /**
     * Route to create a new plant
     * @Route("/api/plants", name="api_plant_create", methods={"POST"})
     */
    public function createPlant(EntityManagerInterface $entityManager, Request $request, GenreRepository $genreRepository, GardenRepository $gardenRepository): Response
    {
        $data = $request->getContent();
        $dataDecoded = json_decode($data);

        $newPlant = new Plant();

        $newPlant->setAge('0');
        $newPlant->setGenre($genreRepository->find($dataDecoded->genre));
        $newPlant->setHydration($newPlant->getGenre()->getMaxHydration());
        $newPlant->setCreatedAt(new DateTimeImmutable('now'));

        $newPlant->setGarden($gardenRepository->find($dataDecoded->garden));

        $entityManager->persist($newPlant);
        $entityManager->flush();

        return $this->json(
            $newPlant,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['show_plant']]
        );
    }
}
