<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column('idCategory')]
    private ?int $idCategory = null;

    #[ORM\Column(length: 64)]
    private ?string $category = null;

    #[ORM\OnetoMany(targetEntity: Product::class, mappedBy: "categorie", fetch: "LAZY")]
    private $products;

    public function getIdCategory(): ?int
    {
        return $this->idCategory;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }
}
