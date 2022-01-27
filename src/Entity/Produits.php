<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Nom;

    #[ORM\Column(type: 'integer')]
    private $Qty;

    #[ORM\OneToMany(mappedBy: 'Produits', targetEntity: LCommandes::class)]
    private $Produits;

    #[ORM\ManyToMany(targetEntity: Fournisseurs::class, mappedBy: 'Produits')]
    private $Fournisseurs;

    #[ORM\ManyToMany(targetEntity: Categories::class, mappedBy: 'Produits')]
    private $Categories;

<<<<<<< HEAD
    private $Utilisateurs;

=======
>>>>>>> b290a8135694e0ba591d6b55d27af8b546356079
    #[ORM\ManyToMany(targetEntity: Panier::class, mappedBy: 'Produits')]
    private $paniers;

    #[ORM\ManyToMany(targetEntity: Utilisateurs::class, mappedBy: 'produits')]
    private $utilisateurs;



    public function __construct()
    {
        $this->Produits = new ArrayCollection();
        $this->Fournisseurs = new ArrayCollection();
        $this->Categories = new ArrayCollection();
        $this->Utilisateurs = new ArrayCollection();
        $this->paniers = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->Qty;
    }

    public function setQty(int $Qty): self
    {
        $this->Qty = $Qty;

        return $this;
    }

    /**
     * @return Collection|LCommandes[]
     */
    public function getProduits(): Collection
    {
        return $this->Produits;
    }

    public function addProduit(LCommandes $produit): self
    {
        if (!$this->Produits->contains($produit)) {
            $this->Produits[] = $produit;
            $produit->setProduits($this);
        }

        return $this;
    }

    public function removeProduit(LCommandes $produit): self
    {
        if ($this->Produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getProduits() === $this) {
                $produit->setProduits(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Fournisseurs[]
     */
    public function getFournisseurs(): Collection
    {
        return $this->Fournisseurs;
    }

    public function addFournisseur(Fournisseurs $fournisseur): self
    {
        if (!$this->Fournisseurs->contains($fournisseur)) {
            $this->Fournisseurs[] = $fournisseur;
            $fournisseur->addProduit($this);
        }

        return $this;
    }

    public function removeFournisseur(Fournisseurs $fournisseur): self
    {
        if ($this->Fournisseurs->removeElement($fournisseur)) {
            $fournisseur->removeProduit($this);
        }

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategories(): Collection
    {
        return $this->Categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->Categories->contains($category)) {
            $this->Categories[] = $category;
            $category->addProduit($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if ($this->Categories->removeElement($category)) {
            $category->removeProduit($this);
        }

        return $this;
    }

    /**
     * @return Collection|Utilisateurs[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->Utilisateurs;
    }

    public function addUtilisateur(Utilisateurs $utilisateur): self
    {
        if (!$this->Utilisateurs->contains($utilisateur)) {
            $this->Utilisateurs[] = $utilisateur;
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateurs $utilisateur): self
    {
        $this->Utilisateurs->removeElement($utilisateur);

        return $this;
    }

    /**
     * @return Collection|Panier[]
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->addProduit($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            $panier->removeProduit($this);
        }

        return $this;
    }

}
