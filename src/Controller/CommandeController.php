<?php

namespace App\Controller;

use App\Core\Notification;
use App\Core\NotificationColor;
use App\Entity\Commande;
use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\TextUI\Command;
use Symfony\Component\HttpFoundation\Request;

use Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Length;

class CommandeController extends AbstractController
{
    private $panier;
    private $em = null;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/commande', name: 'app_commande')]
    public function index(Request $request): Response
    {
        // Revue
        // Vérification d'acces
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->initSession($request);
        $session = $request->getSession();
        if (sizeOf($session->get('panier')->getPanier()) <= 0) {
            return $this->redirectToRoute('app_panier');
        }

        return $this->render('commande/commande.html.twig', [
            'listAchat' => $this->panier
        ]);
    }

    #[Route('/commande/{idCommande}', name: 'app_appercucommande')]
    public function commandeId($idCommande): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $commande = $this->retrieveCommandeById($idCommande);

        // S'il a pas de commande corespondant
        if ($commande == null) {
            return $this->redirectToRoute('app_commande');
        }
        if ($commande->estAMoi($this->getUser())) {
            return $this->render('commande/apercuCommande.html.twig', [
                'commande' => $commande
            ]);
        } else {
            return $this->redirectToRoute('app_commande');
        }
    }

    #[Route('/stripe', name: 'app_stripe_payement')]
    public function stripePayement(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $client = $this->getUser();
        $this->initSession($request);

        \Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        $successURL = $this->generateUrl('stripe-success', [], UrlGeneratorInterface::ABSOLUTE_URL) . "?stripe_id={CHECKOUT_SESSION_ID}";

        $sessionData = [
            'line_items' => [[
                'quantity' => 1,
                'price_data' => ['unit_amount' => $this->prixEnCenne(), 'currency' => 'CAD', 'product_data' => ['name' => 'KeyBoardShop_Achat']],
            ]],
            'customer_email' => $client->getCourriel(),
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'success_url' => $successURL,
            'cancel_url' => $this->generateUrl('stripe-cancel', [], UrlGeneratorInterface::ABSOLUTE_URL)
        ];

        $checkoutSession = \Stripe\Checkout\Session::create($sessionData);
        return $this->redirect($checkoutSession->url, 303);
    }

    #[Route('/stripe-success', name: 'stripe-success')]
    public function stripeSuccess(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->initSession($request);
        $session = $request->getSession();

        // Vérifier que sa bien fonctionner 
        try {
            $stripe = new \Stripe\StripeClient($_ENV["STRIPE_SECRET"]);

            $stripeSessionId = $request->query->get('stripe_id');
            $sessionStripe = $stripe->checkout->sessions->retrieve($stripeSessionId);
            $stripeIntent = $sessionStripe->payment_intent;

            $newCommande = new Commande($this->getUser(), $this->panier, $stripeIntent);

            $message = "";

            foreach ($newCommande->getAchat() as $achat) {
                // TODO: Le merge se fait mal...
                $produit = $this->em->merge($achat->getProduit());
                // Enlever la quantité dans le produit avec une méthode sold dans produit
                if ($produit->vendu($achat->getQuantite())) {
                    $message += "Le produit {$produit->getName()} n'est plus en stock.";
                }
                // Re-set le produit dans l'achat
                $achat->setProduit($produit);
            }
            $this->em->persist($newCommande);
            $this->em->flush();
            // Vider le panier
            $session->remove("panier");
            // Notification
            if ($message != "") {
                $message += "Il se peut que votre commande prendre plus de temps à arriver.";
            }
            $this->addFlash('commande', new Notification('success', $message, NotificationColor::INFO));
        } catch (\Exception $e) {
            return $this->redirectToRoute('app_panier');
        }
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/stripe-cancel', name: 'stripe-cancel')]
    public function stripeCancel(): Response
    {
        return $this->redirectToRoute('app_panier');
    }

    private function retrieveCommandeById($idCommande)
    {
        return $this->em->getRepository(Commande::class)->find($idCommande);
    }

    private function initSession(Request $request)
    {
        $session = $request->getSession();

        $session->set('name', 'William');

        $this->panier = $session->get('panier', new Panier());
        $session->set('panier', $this->panier);
    }

    private function prixEnCenne()
    {
        return round($this->panier->getTotal(), 2) * 100;
    }
}
