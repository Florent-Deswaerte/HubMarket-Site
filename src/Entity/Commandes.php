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
    private $stripeToken;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $brandStripe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $last4Stripe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $idChargeStripe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $statusStripe;

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
        return $this->stripeToken;
    }

    public function setStripeToken(?string $stripeToken): self
    {
        $this->stripeToken = $stripeToken;

        return $this;
    }

    public function getBrandStripe(): ?string
    {
        return $this->brandStripe;
    }

    public function setBrandStripe(?string $brandStripe): self
    {
        $this->brandStripe = $brandStripe;

        return $this;
    }

    public function getLast4Stripe(): ?string
    {
        return $this->last4Stripe;
    }

    public function setLast4Stripe(?string $last4Stripe): self
    {
        $this->last4Stripe = $last4Stripe;

        return $this;
    }

    public function getIdChargeStripe(): ?string
    {
        return $this->idChargeStripe;
    }

    public function setIdChargeStripe(?string $idChargeStripe): self
    {
        $this->idChargeStripe = $idChargeStripe;

        return $this;
    }

    public function getStatusStripe(): ?string
    {
        return $this->statusStripe;
    }

    public function setStatusStripe(?string $statusStripe): self
    {
        $this->statusStripe = $statusStripe;

        return $this;
    }

    public function getData(): array
    {
        $lCommandes = array();
        foreach($this->getCommandes() as $lCommande){
            array_push($lCommandes, $lCommande->getData());
        }
        $data = array(
            'id'=>$this->getId(),
            'date_commande'=>$this->getDateCommande()->format('Y-m-d H:i:s'),
            'date_arrivee'=>$this->getDateArrivee()->format('Y-m-d H:i:s'),
            'total_commande'=>$this->getTotalCommande(),
            'status'=>$this->getStatusStripe(),
            'lCommandes'=>$lCommandes
        );
        return $data;
    }
}
