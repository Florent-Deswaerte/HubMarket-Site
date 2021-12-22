<?php

namespace App\Entity;

use App\Repository\LCommandesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LCommandesRepository::class)]
class LCommandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $Qty;

    #[ORM\Column(type: 'float')]
    private $Prix;

    #[ORM\ManyToOne(targetEntity: Commandes::class, inversedBy: 'Commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private $Commandes;

    #[ORM\ManyToOne(targetEntity: Produits::class, inversedBy: 'Produits')]
    #[ORM\JoinColumn(nullable: false)]
    private $Produits;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getCommandes(): ?Commandes
    {
        return $this->Commandes;
    }

    public function setCommandes(?Commandes $Commandes): self
    {
        $this->Commandes = $Commandes;

        return $this;
    }

    public function getProduits(): ?Produits
    {
        return $this->Produits;
    }

    public function setProduits(?Produits $Produits): self
    {
        $this->Produits = $Produits;

        return $this;
    }
}
