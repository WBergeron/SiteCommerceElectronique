<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ORM\Table(name: 'categories')]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column('idCategorie')]
    private ?int $idCategorie = null;

    #[ORM\Column(length: 64)]
    private ?string $categorie = null;

    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: "categorie", cascade: ["persist"])]
    private $produits;

    public function getIdCategorie(): ?int
    {
        return $this->idCategorie;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie($categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getProduits(): Collection
    {
        return $this->produits;
    }
}
