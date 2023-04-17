<?php

namespace App\Controller;

use App\Core\Notification;
use App\Core\NotificationColor;
use App\Form\ModificationClientFormType;
use App\Entity\Client;
use App\Form\ModificationMDPFormType;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        Request $request,
        Security $security,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var \App\Entity\Client $clienActuel */
        $clientActuel = $this->getUser();
        $clientActuel = (object)$clientActuel;

        /// Form de modification des infos du Client
        // Doit faire la manip d'attribuer les valeur du Form
        $formClient = $this->createForm(ModificationClientFormType::class, $clientActuel);
        $formClient->handleRequest($request);

        if ($formClient->isSubmitted() && $formClient->isValid()) {
            $entityManager->persist($clientActuel);
            $entityManager->flush();

            $security->login($clientActuel);
        }

        /// Form de modification du MTD du Client
        $formPassword = $this->createForm(ModificationMDPFormType::class);
        $formPassword->handleRequest($request);

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $factory = new PasswordHasherFactory([
                'sodium' => ['algorithm' => 'sodium']
            ]);

            $hasher = $factory->getPasswordHasher('sodium');

            // Vérification si le password actuel entrer est valide
            if ($hasher->verify($clientActuel->getPassword(), $formPassword->get('passwordActuel')->getData())) {
                // On peut prendre le nouveau mot de passe pour le mettre en base de données
                // encode the plain password (le nouveau)
                $clientActuel->setPassword(
                    $userPasswordHasher->hashPassword(
                        $clientActuel,
                        $formPassword->get('passwordModif')->getData()
                    )
                );

                $entityManager->persist($clientActuel);
                $entityManager->flush();

                $security->login($clientActuel);

                return $this->redirectToRoute('app_profile');
            }
        }

        return $this->render('profile/profile.html.twig', [
            'clientActuel' => $clientActuel,
            'modificationClientForm' => $formClient->createView(),
            'modificationMDPForm' => $formPassword->createView(),
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
