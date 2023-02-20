<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
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
        $searchField = $request->$request->get('search_field');

        $categories = $this->retrieveAllCategories();

        $products = $this->retrieveProducts($categorie, $searchField);

        return $this->render('catalogue/catalogue.html.twig', ['product' => $products, 'category' => $categories]);
    }

    private function retrieveProducts($categorie, $searchField)
    {
        return $this->em->getRepository(Product::class)->findWithCriteria($categorie, $searchField);
    }

    private function retrieveAllCategories()
    {
        return $this->em->getRepository(Category::class)->findAll();
    }
}
