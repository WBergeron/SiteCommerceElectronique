<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Form\CategorieCollection;
use App\Form\CategorieCollectionType;
use App\Form\ModifAjoutProduitFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/admin/categories', name: 'app_adminCategories')]
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_profile');
        }

        $categories = $this->em->getRepository(Categorie::class)->findAll();
        $categoriesCollection = new CategorieCollection($categories);

        $formCategories = $this->createForm(CategorieCollectionType::class, $categoriesCollection);
        $formCategories->handleRequest($request);

        if ($formCategories->isSubmitted() && $formCategories->isValid()) {
            $newCollectionCategories = $formCategories->getData()->getCategories();
            foreach ($newCollectionCategories as $newCategorie) {
                $this->em->persist($newCategorie);
            }
            $this->em->flush();
        }

        return $this->render('admin/categories.html.twig', [
            'formCategories' => $formCategories
        ]);
    }

    #[Route('/admin/commandes', name: 'app_adminCommandes')]
    public function adminCommandes(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_profile');
        }

        $commandes = $this->em->getRepository(Commande::class)->findAllAndOrderByDate();

        return $this->render('admin/commandes.html.twig', [
            'listeCommandes' => $commandes
        ]);
    }

    #[Route('/admin/products', name: 'app_adminProduits')]
    public function adminProducts(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_profile');
        }

        $produits = $this->em->getRepository(Produit::class)->findAll();

        return $this->render('admin/produits.html.twig', [
            'listeProduits' => $produits
        ]);
    }

    #[Route('/admin/products/{idProduit}', name: 'app_adminModifierProduit')]
    public function adminModifProducts($idProduit, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_profile');
        }

        $produit = $this->em->getRepository(Produit::class)->find($idProduit);
        $formProduit = $this->createForm(ModifAjoutProduitFormType::class, $produit);
        $formProduit->handleRequest($request);

        if ($formProduit->isSubmitted() && $formProduit->isValid()) {
            $this->em->persist($produit);
            $this->em->flush();
        }

        return $this->render('admin/modifAjoutProduit.html.twig', [
            'formProduit' => $formProduit
        ]);
    }
}
