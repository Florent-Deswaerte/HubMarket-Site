<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $DateCommande;

    #[ORM\Column(type: 'date')]
    private $DateArrivee;

    #[ORM\Column(type: 'float')]
    private $TotalCommande;

    #[ORM\OneToMany(mappedBy: 'Commandes', targetEntity: LCommandes::class)]
    private $Commandes;

    private $utilisateurs;

    public function __construct()
    {
        $this->Commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->DateCommande;
    }

    public function setDateCommande(\DateTimeInterface $DateCommande): self
    {
        $this->DateCommande = $DateCommande;

        return $this;
    }

    public function getDateArrivee(): ?\DateTimeInterface
    {
        return $this->DateArrivee;
    }

    public function setDateArrivee(\DateTimeInterface $DateArrivee): self
    {
        $this->DateArrivee = $DateArrivee;

        return $this;
    }

    public function getTotalCommande(): ?float
    {
        return $this->TotalCommande;
    }

    public function setTotalCommande(float $TotalCommande): self
    {
        $this->TotalCommande = $TotalCommande;

        return $this;
    }

    /**
     * @return Collection|LCommandes[]
     */
    public function getCommandes(): Collection
    {
        return $this->Commandes;
    }

    public function addCommande(LCommandes $commande): self
    {
        if (!$this->Commandes->contains($commande)) {
            $this->Commandes[] = $commande;
            $commande->setCommandes($this);
        }

        return $this;
    }

    public function removeCommande(LCommandes $commande): self
    {
        if ($this->Commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getCommandes() === $this) {
                $commande->setCommandes(null);
            }
        }

        return $this;
    }

    public function getUtilisateurs(): ?Utilisateurs
    {
        return $this->utilisateurs;
    }

    public function setUtilisateurs(?Utilisateurs $utilisateurs): self
    {
        $this->utilisateurs = $utilisateurs;

        return $this;
    }
}
