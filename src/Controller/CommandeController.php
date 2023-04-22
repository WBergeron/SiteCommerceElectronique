<?php

namespace App\Controller;

use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommandeController extends AbstractController
{
    private $panier;
    private $em = null;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    #[Route('/commande', name: 'app_commande')]
    public function index(Request $request): Response
    {
        // Revue
        // Vérification d'acces
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->initSession($request);
        $session = $request->getSession();
        if ($session->get('data')) {
            return $this->redirectToRoute('app_connection');
        }

        return $this->render('commande/commande.html.twig', [
            'listAchat' => $this->panier
        ]);
    }

    private function initSession(Request $request)
    {
        $session = $request->getSession();

        $session->set('name', 'William');

        $this->panier = $session->get('panier', new Panier());
        $session->set('panier', $this->panier);
    }
}
