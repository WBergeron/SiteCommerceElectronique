<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\Table(name: 'produits')]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column('idProduit')]
    private ?int $idProduit = null;

    #[ORM\Column(length: 128)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $quantityInStock = null;

    #[ORM\Column(length: 1024)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePath = null;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: "produits", cascade: ["persist"])]
    #[ORM\JoinColumn(name: 'categorie', referencedColumnName: 'idCategorie')]
    private $categorie;

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getIdCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getQuantityInStock(): ?float
    {
        return $this->quantityInStock;
    }

    public function setQuantityInStock(float $quantityInStock): self
    {
        $this->price = $quantityInStock;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->description = $imagePath;
        return $this;
    }
}
