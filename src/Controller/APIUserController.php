<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class APIUserController extends AbstractController
{
    /**
     * Route to create a new user
     * @Route("/api/users", name="api_user_create", methods={"POST"})
     */
    public function createPlant(EntityManagerInterface $entityManager, Request $request, MailerInterface $mailer): Response
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

        // Sending an email once user registered
        $email = (new Email())
        ->from('hello@example.com')
        ->to($newUser->getEmail())
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Tu viens de d\'inscrire à notre application GreenApp')
        ->text('Salut ' . $newUser->getName() . ' ! Tu viens juste de t\'inscrire à notre application ! Tu peux maintenant créer ton premier jardin et palnter ta première graine !')
        ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return $this->json(
            $newUser,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['show_user']]
        );
    }
}
