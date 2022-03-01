<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'method' => 'POST',
            'path' => '/commandes',
            'route_name' => 'apiPostCommandes',
            'openapi_context' => [
                'summary' => 'Crée une commande',
                'description' => 'Crée une commande',
                'requestBody' => [
                    'content' => [],
                ]
            ]
        ],
        'postProduit' => [
            'method' => 'POST',
            'path' => '/commandes/add/produits/{id}',
            'route_name' => 'apiAddCommandesProduit',
            'openapi_context' => [
                'summary' => 'Ajouter un produit à une commande',
                'description' => 'Ajouter un produit à une commande',
                'parameters' => [
                    [
                        'in' => 'path',
                        'name' => 'id',
                        'description' => 'Identifiant de la commande',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer'
                        ]
                    ]
                        ],
                'requestBody' => [
                    'content' => [],
                ]
            ]
        ],
        'get' => [
            'method' => 'GET',
            'path' => '/commandes/get',
            'route_name' => 'apiGetCommandes',
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => 'Récupère la liste des commandes',
                'description' => 'Récupère la liste des commandes',
                'parameters' => [],
            ],
        ],
    ],
    itemOperations: [
        'patchCommandes'=> [
            'method' => 'PATCH',
            'filters' => [],
            'openapi_context' => [
                'summary' => 'Modifie une commande',
                'description' => 'Modifie un commande',
                'parameters' => [
                ],
                'requestBody' => [
                    'content' => [],
                ]
            ]
        ],
        'deleteCommandes'=> [
            'method' => 'DELETE',
            'path' => '/commandes/{id}',
            'route_name' => 'apiDeleteCommandes',
            'filters' => [],
            'openapi_context' => [
                'summary' => 'Supprime une commande',
                'description' => 'Supprime une commande',
                'parameters' => [
                    [
                        'in' => 'path',
                        'name' => 'id',
                        'description' => 'Identifiant du panier',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer'
                        ]
                    ]
                ]
            ]
        ],
    ]
)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $DateCommande;

    #[ORM\Column(type: 'date', nullable: true)]
    private $DateArrivee;

    #[ORM\Column(type: 'float')]
    private $TotalCommande;

    #[ORM\OneToMany(mappedBy: 'Commandes', targetEntity: LCommandes::class)]
    private $Commandes;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class, inversedBy: 'commande')]
    private $utilisateurs;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $stripe_token;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $brand_stripe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $last4_stripe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $id_charge_stripe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $status_stripe;

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

    public function getStripeToken(): ?string
    {
        return $this->stripe_token;
    }

    public function setStripeToken(?string $stripe_token): self
    {
        $this->stripe_token = $stripe_token;

        return $this;
    }

    public function getBrandStripe(): ?string
    {
        return $this->brand_stripe;
    }

    public function setBrandStripe(?string $brand_stripe): self
    {
        $this->brand_stripe = $brand_stripe;

        return $this;
    }

    public function getLast4Stripe(): ?string
    {
        return $this->last4_stripe;
    }

    public function setLast4Stripe(?string $last4_stripe): self
    {
        $this->last4_stripe = $last4_stripe;

        return $this;
    }

    public function getIdChargeStripe(): ?string
    {
        return $this->id_charge_stripe;
    }

    public function setIdChargeStripe(?string $id_charge_stripe): self
    {
        $this->id_charge_stripe = $id_charge_stripe;

        return $this;
    }

    public function getStatusStripe(): ?string
    {
        return $this->status_stripe;
    }

    public function setStatusStripe(?string $status_stripe): self
    {
        $this->status_stripe = $status_stripe;

        return $this;
    }

    public function getData(): array
    {
        $data = array(
            'id'=>$this->getId(),
            'date_commande'=>$this->getDateCommande(),
            'date_arrivee'=>$this->getDateArrivee(),
            'total_commande'=>$this->getTotalCommande(),
            'utilisateurs'=>$this->getUtilisateurs(),
            'status'=>$this->getStatusStripe(),
        );
        return $data;
    }
}
