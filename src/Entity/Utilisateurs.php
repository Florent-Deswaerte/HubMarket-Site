<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
class Utilisateurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Nom;

    #[ORM\Column(type: 'string', length: 255)]
    private $Prenom;

    #[ORM\Column(type: 'string', length: 255)]
    private $Adresse;

    #[ORM\Column(type: 'integer')]
    private $Code_Postal;

    #[ORM\Column(type: 'string', length: 255)]
    private $Pays;

    #[ORM\Column(type: 'string', length: 255)]
    private $Mdp;

    #[ORM\OneToMany(mappedBy: 'Utilisateurs', targetEntity: Commandes::class)]
    private $Utilisateurs;

    #[ORM\OneToOne(mappedBy: 'Utilisateurs', targetEntity: Panier::class, cascade: ['persist', 'remove'])]
    private $panier;

    #[ORM\ManyToMany(targetEntity: Produits::class, mappedBy: 'Utilisateurs')]
    private $produits;


    public function __construct()
    {
        $this->Utilisateurs = new ArrayCollection();
        $this->produits = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->Code_Postal;
    }

    public function setCodePostal(int $Code_Postal): self
    {
        $this->Code_Postal = $Code_Postal;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->Pays;
    }

    public function setPays(string $Pays): self
    {
        $this->Pays = $Pays;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->Mdp;
    }

    public function setMdp(string $Mdp): self
    {
        $this->Mdp = $Mdp;

        return $this;
    }

    /**
     * @return Collection|Commandes[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->Utilisateurs;
    }

    public function addUtilisateur(Commandes $utilisateur): self
    {
        if (!$this->Utilisateurs->contains($utilisateur)) {
            $this->Utilisateurs[] = $utilisateur;
            $utilisateur->setUtilisateurs($this);
        }

        return $this;
    }

    public function removeUtilisateur(Commandes $utilisateur): self
    {
        if ($this->Utilisateurs->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getUtilisateurs() === $this) {
                $utilisateur->setUtilisateurs(null);
            }
        }

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(Panier $panier): self
    {
        // set the owning side of the relation if necessary
        if ($panier->getUtilisateurs() !== $this) {
            $panier->setUtilisateurs($this);
        }

        $this->panier = $panier;

        return $this;
    }

    /**
     * @return Collection|Produits[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produits $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->addUtilisateur($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeUtilisateur($this);
        }

        return $this;
    }

}
