<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'method' => 'POST',
            'path' => '/panier',
            'route_name' => 'apiPostPanier',
            'openapi_context' => [
                'summary' => 'Crée un panier',
                'description' => 'Crée un panier',
                'requestBody' => [
                    'content' => [],
                ]
            ]
        ],
        'get' => [
            'method' => 'GET',
            'path' => '/panier',
            'route_name' => 'apiGetPanier',
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => 'Récupère la liste des paniers',
                'description' => 'Récupère la liste des paniers',
                'parameters' => [],
            ],
        ],
    ],
    itemOperations: [
        'patchPanier'=> [
            'method' => 'PATCH',
            'filters' => [],
            'openapi_context' => [
                'summary' => 'Modifie un panier',
                'description' => 'Modifie un panier',
                'parameters' => [
                ],
                'requestBody' => [
                    'content' => [],
                ]
            ]
        ],
        'deletePanier'=> [
            'method' => 'DELETE',
            'path' => '/panier/{id}',
            'route_name' => 'apiDeletePanier',
            'filters' => [],
            'openapi_context' => [
                'summary' => 'Supprime un panier',
                'description' => 'Supprime un panier',
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
        ]
    ]
)]
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

    public function getData(): array
    {
        $data = array(
            'id'=>$this->id,
            'utilisateurs'=>$this->utilisateurs,
            'Produits'=>$this->Produits,
        );
        return $data;
    }
}
