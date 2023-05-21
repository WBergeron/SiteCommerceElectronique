<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Commande;
use App\Form\CategorieCollection;
use App\Form\CategorieCollectionType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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

        // La vérification de cette ligne la fonctionne pas...
        // $formCategories->handleRequest($request);

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
}
