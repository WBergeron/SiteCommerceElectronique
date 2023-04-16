<?php

namespace App\Controller;

use App\Core\Notification;
use App\Core\NotificationColor;
use App\Form\ModificationClientFormType;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        Request $request,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $clientActuel = $this->getUser();

        // Doit faire la manip d'attribuer les valeur du Form
        $form = $this->createForm(ModificationClientFormType::class, $clientActuel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($clientActuel);
            $entityManager->flush();

            $security->login($clientActuel);
        }

        return $this->render('profile/profile.html.twig', [
            'clientActuel' => $clientActuel,
            'modificationForm' => $form->createView(),
        ]);
    }

    #[Route('/connection', name: 'app_connection')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profile');
        }

        $notification = null;
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error != null && $error->getMessageKey() === 'Invalid credentials.') {
            $message = "Mauvaise combinaison de courriel et de mot de passe.";
            $notification = new Notification('error', $message, NotificationColor::WARNING);
        }

        $dernier_courriel = $authenticationUtils->getLastUsername();

        return $this->render('profile/connection.html.twig', [
            'dernier_courriel' => $dernier_courriel,
            'notification' => $notification
        ]);
    }

    #[Route('/deconnection', name: 'app_deconnection')]
    public function deconnection()
    {
        throw new \Exception("Don't forget to activate logout in security.yaml");
    }
}
