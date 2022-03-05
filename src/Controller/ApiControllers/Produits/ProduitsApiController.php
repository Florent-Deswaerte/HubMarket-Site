<?php
namespace App\Controller\ApiControllers\Produits;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\ApiControllers\APIUtilities;
use App\Controller\HelperControllers\HelperController;
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

    public function __construct(ProduitsRepository $produitsRepository, APIUtilities $apiUtilities, HelperController $utilities, FournisseursRepository $fournisseursRepository, CategoriesRepository $categoriesRepository)
    {
        $this->produitsRepository = $produitsRepository;
        $this->apiUtilities = $apiUtilities;
        $this->utilities = $utilities;
        $this->fournisseursRepository = $fournisseursRepository;
        $this->categoriesRepository = $categoriesRepository;
    }

    // POST
    #[Route("/api/produits", name: 'apiPostProduit', methods: 'POST')]
    public function postProduit(Request $request){
        $produit = new Produits(); //
        $nomFournisseur = $request->get('fournisseur');
        $nomCategorie = $request->get('categorie');
        $nomProduit = $request->get('nom');
        $qtyProduit = $request->get('qty');
        $prixProduit = $request->get('prix');
        $produit->setPrix($prixProduit);
        $produit->setNom($nomProduit);
        $produit->setQty($qtyProduit);
        $fournisseur = $this->fournisseursRepository->findOneByLibelle($nomFournisseur);
        if(!is_null($fournisseur)) {
            $produit->addFournisseur($fournisseur); 
            $categorie = $this->categoriesRepository->findOneByName($nomCategorie);
            if(!is_null($categorie)){
                $produit->addCategory($categorie);
                $this->utilities->saveEntityObject($produit); //
                $data = $this->apiUtilities->formatData($produit);
                $responseArray = array('api:responseCode'=>201, 'api:responseInfo' => "Le produit a bien été créé", 'api:membersCount'=>1,'api:members' => $data ); 
                return $this->apiUtilities->JSONResponseCreated($responseArray);
            } 
            else{
                return $this->apiUtilities->NotFoundResponse("Aucune catégorie de ce nom trouvé!");
            }
        }
        else {
            return $this->apiUtilities->NotFoundResponse("Aucun fournisseur possédant ce libelle trouvé!");
        }
    }

    // PATCH
    #[Route("/api/produits/{id}", name: 'apiPatchProduit', methods: 'PATCH')]
    public function patchProduit(int $id, Request $request){
        $produit = $this->produitsRepository->findOneById($id);
        if(is_null($produit)) {
            return $this->apiUtilities->NotFoundResponse("Aucun produit avec cet identifiant trouvé");
        }
        $nomFournisseur = $request->get('fournisseur');
        $nomCategorie = $request->get('categorie');
        $nomProduit = $request->get('nom');
        $qtyProduit = $request->get('qty');
        $prixProduit = $request->get('prix');
        $produit->setPrix($prixProduit);
        $produit->setNom($nomProduit);
        $produit->setQty($qtyProduit);
        $fournisseur = $this->fournisseursRepository->findOneByLibelle($nomFournisseur);
        if(!is_null($fournisseur)) {
            $produit->addFournisseur($fournisseur);
            $categorie = $this->categoriesRepository->findOneByName($nomCategorie);
            if(!is_null($categorie)){
                $produit->addCategory($categorie);
                $this->utilities->saveEntityObject($produit);
                $data = $this->apiUtilities->formatData($produit);
                $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "Le produit a bien été modifié", 'api:membersCount'=>1,'api:members' => $data ); 
                return $this->apiUtilities->JSONResponseOk($responseArray);
            } 
            else{
                return $this->apiUtilities->NotFoundResponse("Aucune catégorie de ce nom trouvé!");
            }
        }
        else {
            return $this->apiUtilities->NotFoundResponse("Aucun fournisseur possédant ce libelle trouvé!");
        }
    }

    //DELETE
    #[Route("/api/produits/{id}", name: 'apiDeleteProduit', methods: 'DELETE')]
    public function deleteProduit(int $id){
        $produit = $this->produitsRepository->findOneById($id);
        if(is_null($produit)){
            return $this->apiUtilities->NotFoundResponse("Aucun produit avec cet identifiant trouvé");
        }
        $data = $this->apiUtilities->formatData($produit);
        $responseArray = array('api:responseCode'=>204, 'api:responseInfo' => "Le produit a bien été supprimé", 'api:membersCount'=>1,'api:members' => $data ); 
        $this->utilities->removeEntityObject($produit);
        return $this->apiUtilities->JSONResponseDeleted($responseArray);
    }

    // GET (by identifier)
    #[Route("/api/produits/{id}", name: 'apiGetProduitById', methods: 'GET')]
    public function getProduitById(int $id){
        $produit = $this->produitsRepository->findOneById($id);
        if(is_null($produit)){
            return $this->apiUtilities->NotFoundResponse("Aucun produit avec cet identifiant trouvé");
        }
        $data = $this->apiUtilities->formatData($produit);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "Le produit a bien été récupéré", 'api:membersCount'=>1,'api:members' => $data ); 
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }

    // GET (by name)
    #[Route("/api/produits/name/{name}", name: 'apiGetProduitsByName', methods: 'GET')]
    public function getProduitsByName(string $name){
        $produits = $this->produitsRepository->findByName($name);
        if(empty($produits)){
            return $this->apiUtilities->NotFoundResponse("La liste est vide!");
        }
        $data = $this->apiUtilities->formatDataArray($produits);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "La liste des produits a bien été envoyée", 'api:membersCount'=>count($produits),'api:members' => $data ); 
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }

    // GET
    #[Route("/api/produits", name: 'apiGetProduits', methods: 'GET')]
    public function getAllProduits(){
        $produits = $this->produitsRepository->findBy(array(), array('id'=>'ASC'));
        if(empty($produits)){
            return $this->apiUtilities->NotFoundResponse("La liste est vide!");
        }
        $data = $this->apiUtilities->formatDataArray($produits);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "La liste des produits a bien été envoyée", 'api:membersCount'=>count($produits),'api:members' => $data ); 
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }
}