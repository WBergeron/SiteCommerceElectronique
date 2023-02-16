<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column('idProduct')]
    private ?int $idProduct = null;

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

    public function getIdProduct(): ?int
    {
        return $this->idProduct;
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
