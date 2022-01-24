<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\JoinColumn(nullable: false)]
    private $Utilisateurs;

    #[ORM\ManyToMany(targetEntity: Produits::class, inversedBy: 'paniers')]
    private $Produits;

    public function __construct()
    {
        $this->Produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateurs(): ?Utilisateurs
    {
        return $this->Utilisateurs;
    }

    public function setUtilisateurs(Utilisateurs $Utilisateurs): self
    {
        $this->Utilisateurs = $Utilisateurs;

        return $this;
    }

    /**
     * @return Collection|Produits[]
     */
    public function getProduits(): Collection
    {
        return $this->Produits;
    }

    public function addProduit(Produits $produit): self
    {
        if (!$this->Produits->contains($produit)) {
            $this->Produits[] = $produit;
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        $this->Produits->removeElement($produit);

        return $this;
    }
}
