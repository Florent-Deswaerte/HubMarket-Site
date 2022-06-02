<?php

namespace App\Entity;

use App\Repository\FournisseursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: FournisseursRepository::class)]
#[ApiResource(
    normalizationContext:['groups' => ['read:User']],
    collectionOperations: [
        'getFournisseurs' => [
            'method' => 'GET',
            'path' => '/users',
            'route_name' => 'apiGetFournisseurs',
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => 'Récupère la liste des fournisseurs',
                'parameters' => [],
            ],
        ],
        'postFournisseur' => [
            'method' => 'post',
            'path' => '/fournisseurs',
            'route_name' => 'apiPostFournisseur',
            'openapi_context' => [
                'summary' => 'Crée un fournisseur',
                'description' => 'Crée un fournisseur',
                'parameters' => [
                    [
                        'in' => 'query',
                        'name' => 'libelle',
                        'description' => 'Libelle du fournisseur',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'email',
                        'description' => 'Email du fournisseur',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'pays',
                        'description' => 'Pays du fournisseur',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'adresse',
                        'description' => 'Adresse du fournisseur',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'codePostal',
                        'description' => 'Code postal du fournisseur',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                ],
                'requestBody' => [
                    'content' => [],
                ]
            ]
        ],
        'getFournisseurById' => [
            'method' => 'GET',
            'path' => '/fournisseur/{id}',
            'route_name' => 'apiGetFournisseurById',
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => "Récupère un fournisseur par son id",
                'parameters' => [
                    [
                        'in' => 'path',
                        'name' => 'id',
                        'description' => 'Identifiant du fournisseur',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer'
                        ]
                    ]
                ]
            ],
        ],
        'getFournisseurByEmail' => [
            'method' => 'GET',
            'path' => '/fournisseurs/email/{email}',
            'route_name' => 'apiGetFournisseurByEmail',
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => 'Récupère un fournisseur par son email',
                'description' => 'Récupère un fournisseur par son email',
                'parameters' => [
                    [
                        'in' => 'path',
                        'name' => 'email',
                        'description' => 'Email du fournisseur',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ]
                ]
            ]
        ],
        'getFournisseurByLibelle' => [
            'method' => 'GET',
            'path' => '/fournisseurs/libelle/{libelle}',
            'route_name' => 'apiGetFournisseurByName',
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => 'Récupère un fournisseur par son libelle',
                'description' => 'Récupère un fournisseur par son libelle',
                'parameters' => [
                    [
                        'in' => 'path',
                        'name' => 'libelle',
                        'description' => 'Libelle du fournisseur',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ]
                ]
            ]
        ] 
    ],
    itemOperations: [
        'patchUser'=> [
            'method' => 'PATCH',
            'openapi_context' => [
                'summary' => 'Modifie un fournisseur',
                'description' => 'Modifie un fournisseur'
            ]
        ],
        'deleteUser'=> [
            'method' => 'DELETE',
            'path' => '/fournisseurs/{id}',
            'route_name' => 'apiDeleteFournisseur',
            'filters' => [],
            'openapi_context' => [
                'summary' => 'Supprime un fournisseur',
                'description' => 'Supprime un fournisseur'
            ]
        ]
    ],
)]class Fournisseurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Libelle;

    #[ORM\Column(type: 'string', length: 255)]
    private $Adresse;

    #[ORM\Column(type: 'integer')]
    private $Code_Postal;

    #[ORM\Column(type: 'string', length: 255)]
    private $Pays;

    #[ORM\ManyToMany(targetEntity: Produits::class, inversedBy: 'Fournisseurs')]
    private $Produits;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    public function __construct()
    {
        $this->Produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): array
    {
        $data = array(
            'id'=>$this->getId(),
            'libelle'=>$this->getLibelle(),
            'pays'=>$this->getPays(),
            'adresse'=>$this->getAdresse(),
            'cp'=>$this->getCodePostal(),
            'email'=>$this->getEmail(),
        );
        return $data;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(string $Libelle): self
    {
        $this->Libelle = $Libelle;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
