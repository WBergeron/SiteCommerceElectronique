<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\Constraint\Count;
use Symfony\Component\HttpFoundation\Request;

use Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Length;

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
        if (sizeOf($session->get('panier')->getPanier()) <= 0) {
            return $this->redirectToRoute('app_panier');
        }

        return $this->render('commande/commande.html.twig', [
            'listAchat' => $this->panier
        ]);
    }

    #[Route('/stripe', name: 'app_stripe_payement')]
    public function stripePayement(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $client = $this->getUser();

        \Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        $successURL = $this->generateUrl('stripe_success', [], UrlGeneratorInterface::ABSOLUTE_URL) . "?stripe_id={CHECKOUT_SESSION_ID}";

        $sessionData = [
            'line_items' => [[
                'quantity' => 1,
                'price_data' => ['unit_amount' => $this->panier->getTotal(), 'currency' => 'CAD', 'product_data' => []],
            ]],
            'customer_email' => $client->getCourriel(),
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'success_url' => $successURL,
            'cancel_url' => $this->generateUrl('stripe_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL)
        ];

        $checkoutSession = \Stripe\Checkout\Session::create($sessionData);
        return $this->redirect($checkoutSession->url, 303);
    }

    #[Route('/stripe-success', name: 'stripe-success')]
    public function stripeSuccess(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // Dans le TP
        // Créer une commande
        // Transformé le panier en commande
        // MaJ des quantité des produit 
        // Vider le panier

        $user = $this->getUser();

        // Vérifier que sa bien fonctionner 
        try {
            $stripe = new \Stripe\StripeClient($_ENV["STRIPE_SECRET"]);

            $stripeSessionId = $request->query->get('stripe_id');
            $sessionStripe = $stripe->checkout->sessions->retrieve($stripeSessionId);
            $stripeIntent = $sessionStripe->payment_intent;

            foreach ($this->panier as $achat) {
                // !!! DANS LE TP ICI !!! :
                // Il faudra appeler la méthode merge de l'entité manager sur chaque achat
            }

            $newCommande = new Commande($stripeIntent);
            var_dump($newCommande);
            // $this->em->persist($newCommande);
            // $this->em->flush();
        } catch (\Exception $e) {
            return $this->redirectToRoute('app_panier');
        }
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/stripe-cancel', name: 'stripe-cancel')]
    public function stripeCancel(): Response
    {
        return $this->redirectToRoute('app_profile');
    }

    private function initSession(Request $request)
    {
        $session = $request->getSession();

        $session->set('name', 'William');

        $this->panier = $session->get('panier', new Panier());
        $session->set('panier', $this->panier);
    }
}
