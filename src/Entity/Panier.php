<?php

namespace App\Entity;

class Panier
{
    private $achats = [];

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
}
