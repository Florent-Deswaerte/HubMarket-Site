<?php
namespace App\Controller\ApiControllers\Produits;

use App\Entity\Produits;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\ApiControllers\APIUtilities;
use App\Controller\UtilitiesControllers\UtilityController;
use App\Repository\CategoriesRepository;
use App\Repository\FournisseursRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class ProduitsApiController extends AbstractController
{
    private $produitsRepository;
    private $apiUtilities;
    private $utilities;
    private $fournisseursRepository;
    private $categoriesRepository;

    public function __construct(ProduitsRepository $produitsRepository, APIUtilities $apiUtilities, UtilityController $utilities, FournisseursRepository $fournisseursRepository, CategoriesRepository $categoriesRepository)
    {
        $this->produitsRepository = $produitsRepository;
        $this->apiUtilities = $apiUtilities;
        $this->utilities = $utilities;
        $this->fournisseursRepository = $fournisseursRepository;
        $this->categoriesRepository = $categoriesRepository;
    }

    // POST
    #[Route("/api/produits", name: 'apiPostProduit', methods: 'POST')]
    public function postProduit(){
        $produit = new Produits();
        $nomFournisseur = $_GET['fournisseur'];
        $nomCategorie = $_GET['categorie'];
        $nomProduit = $_GET['nom'];
        $produit->setNom($nomProduit);
        $this->utilities->saveEntityObject($produit);
        
    }

    // PATCH
    #[Route("/api/produits/{id}", name: 'apiPatchProduit', methods: 'PATCH')]
    public function patchProduit(int $id){

    }

    //DELETE
    #[Route("/api/produits/{id}", name: 'apiDeleteProduit', methods: 'DELETE')]
    public function deleteProduit(int $id){

    }

    // GET (by identifier)
    #[Route("/api/produits/{id}", name: 'apiGetProduitById', methods: 'GET')]
    public function getProduitById(int $id){

    }

    // GET (by name)
    #[Route("/api/produits/{name}", name: 'apiGetProduitByName', methods: 'GET')]
    public function getProduitByName(string $name){

    }

    // GET
    #[Route("/api/produits", name: 'apiGetProduits', methods: 'GET')]
    public function getAllProduits(){
        $produits = $this->produitsRepository->findBy(array(), array('id'=>'ASC'));
        if(empty($produits)){
            return $this->apiUtilities->NotFoundResponse("La liste est vide!");
        }
        $data = $this->apiUtilities->formatDataArray($produits);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "La liste des utilisateurs a bien été envoyée", 'api:membersCount'=>count($produits),'api:members' => $data ); 
        return $this->apiUtilities->JSONResponse($responseArray);
    }
}