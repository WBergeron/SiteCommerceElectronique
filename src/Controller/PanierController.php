<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Core\Notification;
use App\Core\NotificationColor;
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

        // Notification
        $this->addFlash(
            'validation',
            new Notification('success', 'Le produit a été ajouté dans votre panier', NotificationColor::SUCCESS)
        );

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/update', name: 'update_achat', methods: ['POST'])]
    public function updateTodo(Request $request): Response
    {
        $post = $request->request->all();
        $this->initSession($request);

        $action = $request->request->get('action');

        if ($action == "rafraichir") {
            $this->panier->modifierAchat($post);
            $this->addFlash(
                'validation',
                new Notification('success', 'Votre panier à été mit à jour', NotificationColor::INFO)
            );
        } else if ($action == "vider") {
            $session = $request->getSession();
            $session->remove('panier');
            $this->addFlash(
                'validation',
                new Notification('success', 'Votre panier à été vidé', NotificationColor::INFO)
            );
        }

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/supprimer/{idProduit}', name: 'delete_achat')]
    public function deleteAchat($idProduit, Request $request): Response
    {
        $this->initSession($request);

        // Supprime le produit dans le panier
        $this->panier->supprimerAchat($idProduit);
        // Notification
        $this->addFlash(
            'validation',
            new Notification('success', 'Le produit a été supprimé du panier', NotificationColor::SUCCESS)
        );

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
