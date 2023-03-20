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
        $achat = new Achat($produit, $quantite, $produit->getPrice());
        $this->achats[] = $achat;
    }

    public function supprimerAchat($produit)
    {
        //Regarde s'il est dans le panier
        foreach ($this->achats as $achat) {
            if ($produit == $achat->produit) {
                $achatASupprimer = $achat;
                // Supprime l'achat dans le panier
                unset($this->achats[$achatASupprimer]);
            }
        }
    }

    public function modifierAchat($nouveauPanier)
    {
        // TODO: Appeler cette méthode quand on veut changer un item
    }

    public function getPanier()
    {
        return $this->achats;
    }

    public function getSommaire()
    {
        $this->sommaire = 0;
        foreach ($this->achats as $achat) {
            $this->sommaire += $achat->getPrixAchat();
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
