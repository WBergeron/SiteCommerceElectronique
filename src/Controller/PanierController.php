<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
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
            'listAchat' => $this->panier,
        ]);
    }

    #[Route('/panier/ajout/{idProduit}', name: 'ajout_achat', methods: ['GET'])]
    public function ajoutAchat($idProduit, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->initSession($request);

        // Trouver le produit
        $this->em = $doctrine->getManager();
        $produit = $this->em->getRepository(Produit::class)->find($idProduit);

        // Ajouter le produit dans le panier
        $this->panier->ajoutAchat($produit, 1);

        // TODO : Ajouter si le produit existe déja

        // Notification
        $this->addFlash('validation', 'Produit ajoutée avec succès');

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/supprimer/{idProduit}', name: 'delete_achat')]
    public function deleteAchat($idProduit, Request $request): Response
    {
        $this->initSession($request);

        // Supprime le produit dans le panier
        $this->panier->supprimerAchat($idProduit);
        // Notification
        $this->addFlash('validation', 'Produit supprimer avec succès');

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
