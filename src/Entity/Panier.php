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

    #[ORM\ManyToMany(targetEntity: Produits::class, inversedBy: 'paniers')]
    private $Produits;

    #[ORM\OneToOne(mappedBy: 'panier', targetEntity: Utilisateurs::class, cascade: ['persist', 'remove'])]
    private $utilisateurs;

    public function __construct()
    {
        $this->Produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUtilisateurs(): ?Utilisateurs
    {
        return $this->utilisateurs;
    }

    public function setUtilisateurs(?Utilisateurs $utilisateurs): self
    {
        // unset the owning side of the relation if necessary
        if ($utilisateurs === null && $this->utilisateurs !== null) {
            $this->utilisateurs->setPanier(null);
        }

        // set the owning side of the relation if necessary
        if ($utilisateurs !== null && $utilisateurs->getPanier() !== $this) {
            $utilisateurs->setPanier($this);
        }

        $this->utilisateurs = $utilisateurs;

        return $this;
    }
}
