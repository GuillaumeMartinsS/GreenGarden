<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class APIUserController extends AbstractController
{
    /**
     * Route to create a new user
     * @Route("/api/users", name="api_user_create", methods={"POST"})
     */
    public function createUser(EntityManagerInterface $entityManager, Request $request, MailerInterface $mailer, UserPasswordHasherInterface $hasher): Response
    {
        $data = $request->getContent();
        $dataDecoded = json_decode($data);

        $newUser = new User();

        $newUser->setName($dataDecoded->name);
        $newUser->setEmail($dataDecoded->email);
        $newUser->setPassword($dataDecoded->password);
        $newUser->setPassword($hasher->hashPassword($newUser, $newUser->getPassword()));
        $newUser->setRoles(["ROLE_USER"]);
        $newUser->setPoints(0);

        $entityManager->persist($newUser);
        $entityManager->flush();

        // Sending an email once user registered
        $email = (new TemplatedEmail())
        ->from('admin@admin.com')
        ->to($newUser->getEmail())
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Tu viens de d\'inscrire à notre application GreenApp')
        // ->text('Salut ' . $newUser->getName() . ' ! Tu viens juste de t\'inscrire à notre application ! Tu peux maintenant créer ton premier jardin et palnter ta première graine !')
        ->htmlTemplate('emails/register.html.twig')

        // pass variables (name => value) to the template
        ->context([
        'newUser' => $newUser,
        ]);

        $mailer->send($email);

        return $this->json(
            $newUser,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['show_user']]
        );
    }

    /**
     * Route to update a user
     * @Route("/api/users/edit/{id}", name="api_user_edit", methods={"POST"})
     */
    public function updateUser(EntityManagerInterface $entityManager, Request $request, User $user, UserPasswordHasherInterface $hasher): Response
    {
        if ($request->request->get('name')!== null) {
            $user->setName($request->request->get('name'));
        }

        if ($request->request->get('email')!== null) {
        $user->setEmail($request->request->get('email'));
        }

        if ($request->request->get('password')!== null) {
        $user->setPassword($hasher->hashPassword($user, $request->request->get('password')));
        }

        if ($request->files!== null) {
            $upload = $request->files->get('picture');

            $uploadedName = md5(uniqid()) . '.' . $upload->guessExtension();

            $upload->move(
                $this->getParameter('upload_directory'),
                $uploadedName);

            // Its name goes as a value for the picture property
            $user->setPicture($uploadedName);}

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(
            $user,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['show_user']]
        );
    }
}
