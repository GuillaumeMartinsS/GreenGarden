<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class APIUserController extends AbstractController
{
    /**
     * Route to create a new user
     * @Route("/api/users", name="api_user_create", methods={"POST"})
     */
    public function createPlant(EntityManagerInterface $entityManager, Request $request): Response
    {
        $data = $request->getContent();
        $dataDecoded = json_decode($data);

        $newUser = new User();

        $newUser->setName($dataDecoded->name);
        $newUser->setEmail($dataDecoded->email);
        $newUser->setPassword($dataDecoded->password);
        $newUser->setRoles(["ROLE_USER"]);
        $newUser->setPoints(0);

        $entityManager->persist($newUser);
        $entityManager->flush();

        return $this->json(
            $newUser,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['show_user']]
        );
    }
}
