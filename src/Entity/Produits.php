<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'method' => 'GET',
            'path' => '/produits',
            'route_name' => 'apiGetProduits',
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => 'Récupère la liste des produits',
                'parameters' => [],
            ],
        ],
        'post' => [
            'method' => 'POST',
            'path' => '/produits',
            'route_name' => 'apiPostProduit',
            'openapi_context' => [
                'summary' => 'Crée un produit',
                'description' => 'Crée un produit',
                'parameters' => [
                    [
                        'in' => 'query',
                        'name' => 'fournisseur',
                        'description' => 'Nom du fournisseur',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'nom',
                        'description' => 'Nom du produit',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'categorie',
                        'description' => 'Nom de la catégorie du produit',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'qty',
                        'description' => 'Quantité du produit',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'description',
                        'description' => 'description du produit',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'prix',
                        'description' => 'prix du produit',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'imagePath',
                        'description' => "dossier de l'image du produit",
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
        'getProduitsByName' => [
            'method' => 'GET',
            'path' => '/produits/name/{name}',
            'controller' => 'apiGetProduitsByName',
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => "Récupère un produit par son nom",
                'parameters' => [
                    [
                        'in' => 'path',
                        'name' => 'name',
                        'description' => 'Nom du produit',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ]
                ]
            ],
        ],
    ],
    itemOperations: [
        'patchProduit'=> [
            'method' => 'PATCH',
            'filters' => [],
            'openapi_context' => [
                'summary' => 'Modifie un produit',
                'description' => 'Modifie un produit',
                'parameters' => [
                    [
                        'in' => 'query',
                        'name' => 'fournisseur',
                        'description' => 'Nom du fournisseur',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'nom',
                        'description' => 'Nom du produit',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'categorie',
                        'description' => 'Nom de la catégorie du produit',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'qty',
                        'description' => 'Quantité du produit',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'prix',
                        'description' => 'prix du produit',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer'
                        ]
                    ],
                    
                ],
                'requestBody' => [
                    'content' => [],
                ]
            ]
        ],
        'getProduitById' => [
            'method' => 'GET',
            'path' => '/produits/{id}',
            'route_name' => 'apiGetProduitById',
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => "Récupère un produit par son id",
                'parameters' => [
                    [
                        'in' => 'path',
                        'name' => 'id',
                        'description' => 'Identifiant du produit',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer'
                        ]
                    ]
                ]
            ],
        ],
        'deleteProduit'=> [
            'method' => 'DELETE',
            'path' => '/produits/{id}',
            'route_name' => 'apiDeleteProduit',
            'filters' => [],
            'openapi_context' => [
                'summary' => 'Supprime un produit',
                'description' => 'Supprime un produit',
                'parameters' => [
                    [
                        'in' => 'path',
                        'name' => 'id',
                        'description' => 'Identifiant du produit',
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

    #[ORM\ManyToMany(targetEntity: Panier::class, mappedBy: 'Produits')]
    private $paniers;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2)]
    private $prix;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Image::class)]
    private $images;

    #[ORM\Column(type: 'string', length: 255)]
    private $imagePath;

    public function __construct()
    {
        $this->Produits = new ArrayCollection();
        $this->Fournisseurs = new ArrayCollection();
        $this->Categories = new ArrayCollection();
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

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
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

    public function getData(): array
    {
        $fournisseurs = array();
        foreach($this->getFournisseurs() as $fournisseur){
            array_push($fournisseurs, $fournisseur->getData());
        }
        $categories = array();
        foreach($this->getCategories() as $categorie){
            array_push($categories, $categorie->getData());
        }
        $data = array(
            'id'=>$this->getId(),
            'nom'=>$this->getNom(),
            'qty'=>$this->getQty(),
            'prix'=>$this->getPrix(),
            'description'=>$this->getDescription(),
            'fournisseurs'=>$fournisseurs,
            'categories'=>$categories,
            'imagePath'=>$this->getImagePath(),

        );
        return $data;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }
}
