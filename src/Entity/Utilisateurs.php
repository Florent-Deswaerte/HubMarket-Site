<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use ApiPlatform\Core\Annotation\ApiResource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
#[ApiResource(
    normalizationContext:['groups' => ['read:User']],
    collectionOperations: [
        'getUsers' => [
            'method' => 'GET',
            'path' => '/users',
            'route_name' => 'apiGetUsersList',
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => 'Récupère la liste des utilisateurs',
                'parameters' => [],
            ],
        ],
        'postUser' => [
            'method' => 'post',
            'path' => '/users',
            'route_name' => 'apiCreateUser',
            'openapi_context' => [
                'summary' => 'Crée un utilisateur',
                'description' => 'Crée un utilisateur',
                'parameters' => [
                    [
                        'in' => 'query',
                        'name' => 'email',
                        'description' => 'Email de l\'utilisateur',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ],
                    [
                        'in' => 'query',
                        'name' => 'password',
                        'description' => 'Mot de passe de l\'utilisateur',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ]
                ],
                'requestBody' => [
                    'content' => [],
                ]
            ]
        ],
        'getUserById' => [
            'method' => 'GET',
            'path' => '/users/{id}',
            'route_name' => 'apiGetUserById',
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => "Récupère un utilisateur par son id",
                'parameters' => [
                    [
                        'in' => 'path',
                        'name' => 'id',
                        'description' => 'Identifiant de l\'utilisateur',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer'
                        ]
                    ]
                ]
            ],
        ],
        'getUserByEmail' => [
            'method' => 'GET',
            'path' => '/users/email/{email}',
            'route_name' => 'apiGetUserByEmail',
            'filters' => [],
            'pagination_enabled' => false,
            'openapi_context' => [
                'summary' => 'Récupère un utilisateur par son email',
                'description' => 'Récupère un utilisateur par son email',
                'parameters' => [
                    [
                        'in' => 'path',
                        'name' => 'email',
                        'description' => 'Email de l\'utilisateur',
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
                'summary' => 'Modifie un utilisateur',
                'description' => 'Modifie un utilisateur'
            ]
        ],
        'deleteUser'=> [
            'method' => 'DELETE',
            'path' => '/users/{id}',
            'route_name' => 'apiDeleteUser',
            'filters' => [],
            'openapi_context' => [
                'summary' => 'Supprime un utilisateur',
                'description' => 'Supprime un utilisateur'
            ]
        ]
    ],
)]
#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
class Utilisateurs implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\OneToMany(mappedBy: 'utilisateurs', targetEntity: Commandes::class)]
    private $commande;

    #[ORM\OneToOne(inversedBy: 'utilisateurs', targetEntity: Panier::class, cascade: ['persist', 'remove'])]
    private $panier;

    #[ORM\Column(type: 'string', length: 255)]
    private $adresse;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\Column(type: 'string', length: 255)]
    private $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    private $pays;

    #[ORM\Column(type: 'string', length: 255)]
    private $ville;

    #[ORM\Column(type: 'integer')]
    private $codePostal;

    #[ORM\Column(type: 'integer')]
    private $phone;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->roles = ["ROLE_USER"];
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Commandes[]
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(Commandes $commande): self
    {
        if (!$this->commande->contains($commande)) {
            $this->commande[] = $commande;
            $commande->setUtilisateurs($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): self
    {
        if ($this->commande->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUtilisateurs() === $this) {
                $commande->setUtilisateurs(null);
            }
        }

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
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
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        $this->produits->removeElement($produit);

        return $this;
    }

    public function getData(): array
    {
        $data = array(
            'id'=>$this->id,
            'email'=>$this->email,
            'roles'=>$this->roles,
            'panier'=>$this->panier,
            'commande'=>$this->commande
        );
        return $data;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
