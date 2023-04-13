<?php

namespace App\Controller;

use App\Core\Notification;
use App\Core\NotificationColor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $clientActuel = $this->getUser();

        return $this->render('profile/profile.html.twig', [
            'clientActuel' => $clientActuel,
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
