<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CatalogueController extends AbstractController
{
    private $em = null;

    #[Route('/', name: 'app_catalogue')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->em = $doctrine->getManager();
        $categorie = $request->query->get('categorie'); // $_GET['categorie']
        $searchField = $request->request->get('search_field'); // $_POST['search_field']

        $categories = $this->retrieveAllCategories();

        $produits = $this->retrieveProducts($categorie, $searchField);

        // Pour débug
        // var_dump($products);

        return $this->render('catalogue/catalogue.html.twig', ['produits' => $produits, 'categories' => $categories]);
    }

    private function retrieveProducts($categorie, $searchField)
    {
        return $this->em->getRepository(Produit::class)->findWithCriteria($categorie, $searchField);
    }

    private function retrieveAllCategories()
    {
        return $this->em->getRepository(Categorie::class)->findAll();
    }

    #[Route('/produits/{idProduit}', name: 'produit_modal')]
    public function infoProduit($idProduit, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->em = $doctrine->getManager();
        $produit = $this->em->getRepository(Produit::class)->find($idProduit);

        return $this->render('catalogue/produit.modal.twig', ['produit' => $produit]);
    }
}
