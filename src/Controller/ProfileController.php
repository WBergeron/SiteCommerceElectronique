<?php

namespace App\Controller;

use App\Core\Notification;
use App\Core\NotificationColor;
use App\Form\ModificationClientFormType;
use App\Entity\Client;
use App\Entity\Commande;
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
    private $em = null;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
        $notification = null;

        /// Form de modification des infos du Client
        // Doit faire la manip d'attribuer les valeur du Form
        $formClient = $this->createForm(ModificationClientFormType::class, $clientActuel);
        $formClient->handleRequest($request);

        if ($formClient->isSubmitted() && $formClient->isValid()) {
            $entityManager->persist($clientActuel);
            $entityManager->flush();

            $security->login($clientActuel);
            $message = "Votre compte à bien été modifié";
            $notification = new Notification('success', $message, NotificationColor::SUCCESS);
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

                $message = "Votre Mot de passe à bien été modifié";
                $notification = new Notification('success', $message, NotificationColor::SUCCESS);
            }
        }

        return $this->render('profile/profile.html.twig', [
            'clientActuel' => $clientActuel,
            'modificationClientForm' => $formClient->createView(),
            'modificationMDPForm' => $formPassword->createView(),
            'notification' => $notification
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

    #[Route('/profile/commandes', name: 'app_clientCommandes')]
    public function clientCommandes()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Request au serveur pour les commandes en lien avec le client connecter
        $client = $this->getUser();
        $commandesClient = $this->retrieveCommandes($client->getIdClient());

        return $this->render('profile/commandes.html.twig', [
            'commandesClient' => $commandesClient,
        ]);
    }

    #[Route('/deconnection', name: 'app_deconnection')]
    public function deconnection()
    {
        throw new \Exception("Don't forget to activate logout in security.yaml");
    }

    private function retrieveCommandes($idClient)
    {
        return $this->em->getRepository(Commande::class)->findBy(array('client' => $idClient));
    }
}
