<?php

namespace App\Entity;

class Panier
{
    private $achats = [];
    private $sommaire = 0;
    private $tps = 0;
    private $tvq = 0;
    private $fraisLivraison = 0;
    private $total = 0;

    public function ajoutAchat($produit, $quantite)
    {
        // si existe déja ++quantite
        foreach ($this->achats as $achat) {
            // TODO: Doit ajouter une quantité
            //if ($achat->getProduit() = $produit) {
            //    $quantite++;
            //}
        }
        $achat = new Achat($produit, $quantite, $produit->getPrice());
        $this->achats[] = $achat;
    }

    public function supprimerAchat($index)
    {
        //Regarde s'il est dans le panier
        if (array_key_exists($index, $this->achats)) {
            unset($this->achats[$index]);
        }
    }

    public function modifierAchat($nouveauPanier)
    {
        // Verifier la quantité des produit et les updates
        if (count($this->achats) > 0) {
            $achatsQuantites = $nouveauPanier["quantiteAchat"];

            foreach ($this->achats as $key => $achat) {
                $nouvelleQuantite = $achatsQuantites[$key];
                if ($nouvelleQuantite <= 0) {
                    unset($this->achats[$key]);
                } else {
                    $achat->updateQuantite($nouvelleQuantite);
                }
            }
        }
    }

    public function getPanier()
    {
        return $this->achats;
    }

    public function getSommaire()
    {
        $this->sommaire = 0;
        foreach ($this->achats as $achat) {
            $this->sommaire += ($achat->getPrixAchat() * $achat->getQuantite());
        }
        return $this->sommaire;
    }

    public function getTps()
    {
        return $this->tps = $this->sommaire * (Constantes::$TPS);
    }

    public function getTvq()
    {
        return $this->tvq = $this->sommaire * Constantes::$TVQ;
    }

    public function getFraisLivraison()
    {
        return $this->fraisLivraison = Constantes::$FRAIS_LIVRAISON;
    }

    public function getTotal()
    {
        return $this->total = $this->sommaire + $this->tps + $this->tvq + $this->fraisLivraison;
    }
}
