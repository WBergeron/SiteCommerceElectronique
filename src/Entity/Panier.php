<?php

namespace App\Entity;

class Panier
{
    private $panier = [];

    public function ajoutAchat($produit, $quantite)
    {
        $achat = new Achat($produit, $quantite, $produit->getPrice());
        $this->panier[] = $achat;
    }

    public function supprimerAchat($index)
    {
        if (array_key_exists($index, $this->panier)) {
            unset($this->panier[$index]);
        }
    }

    public function modifierAchat($nouveauPanier)
    {
        // TODO: Appeler cette méthode quand on veut changer un item
    }

    public function getPanier()
    {
        return $this->panier;
    }
}
