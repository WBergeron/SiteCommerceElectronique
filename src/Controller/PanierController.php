<?php

namespace App\Controller;


use App\Entity\Panier;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    private $panier;
    private $em = null;

    #[Route('/panier', name: 'app_panier')]
    public function index(Request $request): Response
    {
        $this->initSession($request);
        $session = $request->getSession();

        return $this->render('page/panier.html.twig', [
            'name' => $session->get('name'),
            'panier' => $this->panier,
        ]);
    }

    #[Route('/panier/ajout/{idProduit}', name: 'ajout_produit', methods: ['POST'])]
    public function ajoutAchat($idProduit, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->initSession($request);

        // Trouver le produit
        $this->em = $doctrine->getManager();
        $produit = $this->em->getRepository(Produit::class)->find($idProduit);

        // Ajouter le produit dans le panier
        $this->panier->add($produit, 1, $produit->price);

        // TODO : Ajouter si le produit existe déja

        // Notification
        $this->addFlash('Valider', 'Produit ajoutée avec succès');

        return $this->redirectToRoute('app_panier');
    }

    private function initSession(Request $request)
    {
        $session = $request->getSession();

        $session->set('name', 'William');

        $this->panier = $session->get('panier', new Panier());
        $session->set('panier', $this->panier);
    }
}
