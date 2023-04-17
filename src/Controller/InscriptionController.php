<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifier si un user est connecter, si c'est le cas, redirect
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profile');
        }

        $client = new Client();
        $form = $this->createForm(RegistrationFormType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $client->setPassword(
                $userPasswordHasher->hashPassword(
                    $client,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($client);
            $entityManager->flush();

            $security->login($client);

            return $this->redirectToRoute('app_catalogue');
        }

        return $this->render('inscription/creationCompte.html.twig', [
            'inscriptionForm' => $form->createView(),
        ]);
    }
}
