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
    private $imagePathProduit;

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
        $descriptionProduit = $request->get('description');
        $imagePathProduit = $request->get('imagePath');

        $produit->setDescription($descriptionProduit);
        $produit->setPrix($prixProduit);
        $produit->setNom($nomProduit);
        $produit->setQty($qtyProduit);
        $produit->setImagePath($imagePathProduit);
        
        $fournisseur = $this->fournisseursRepository->findOneByLibelle($nomFournisseur);
        if(!is_null($fournisseur)) {
            $produit->addFournisseur($fournisseur); 
            $categorie = $this->categoriesRepository->findOneByName($nomCategorie);
            if(!is_null($categorie)){
                $produit->addCategory($categorie);
                $this->utilities->saveEntityObject($produit); //
                $data = $this->apiUtilities->formatData($produit);
                $responseArray = array('api:responseCode'=>201, 'api:responseInfo' => "Le produit a bien ??t?? cr????", 'api:membersCount'=>1,'api:members' => $data ); 
                return $this->apiUtilities->JSONResponseCreated($responseArray);
            } 
            else{
                return $this->apiUtilities->NotFoundResponse("Aucune cat??gorie de ce nom trouv??!");
            }
        }
        else {
            return $this->apiUtilities->NotFoundResponse("Aucun fournisseur poss??dant ce libelle trouv??!");
        }
    }

    // PATCH
    #[Route("/api/produits/{id}", name: 'apiPatchProduit', methods: 'PATCH')]
    public function patchProduit(int $id, Request $request){
        $produit = $this->produitsRepository->findOneById($id);
        if(is_null($produit)) {
            return $this->apiUtilities->NotFoundResponse("Aucun produit avec cet identifiant trouv??");
        }

        $nomFournisseur = $request->get('fournisseur');
        $nomCategorie = $request->get('categorie');
        $nomProduit = $request->get('nom');
        $qtyProduit = $request->get('qty');
        $descriptionProduit = $request->get('description');
        $prixProduit = $request->get('prix');

        $fournisseur = $this->fournisseursRepository->findOneByLibelle($nomFournisseur);

        $produit->setNom($nomProduit);
        $produit->setQty($qtyProduit);
        $produit->setDescription($descriptionProduit);
        $produit->setPrix($prixProduit);
        
        if(!is_null($fournisseur)) {
            $produit->addFournisseur($fournisseur);
            $categorie = $this->categoriesRepository->findOneByName($nomCategorie);
            if(!is_null($categorie)){
                $produit->addCategory($categorie);
                $this->utilities->saveEntityObject($produit);
                $data = $this->apiUtilities->formatData($produit);
                $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "Le produit a bien ??t?? modifi??", 'api:membersCount'=>1,'api:members' => $data ); 
                return $this->apiUtilities->JSONResponseOk($responseArray);
            } 
            else{
                return $this->apiUtilities->NotFoundResponse("Aucune cat??gorie de ce nom trouv??!");
            }
        }
        else {
            return $this->apiUtilities->NotFoundResponse("Aucun fournisseur poss??dant ce libelle trouv??!");
        }
    }

    //DELETE
    #[Route("/api/produits/{id}", name: 'apiDeleteProduit', methods: 'DELETE')]
    public function deleteProduit(int $id){
        $produit = $this->produitsRepository->findOneById($id);
        if(is_null($produit)){
            return $this->apiUtilities->NotFoundResponse("Aucun produit avec cet identifiant trouv??");
        }
        $data = $this->apiUtilities->formatData($produit);
        $responseArray = array('api:responseCode'=>204, 'api:responseInfo' => "Le produit a bien ??t?? supprim??", 'api:membersCount'=>1,'api:members' => $data ); 
        $this->utilities->removeEntityObject($produit);
        return $this->apiUtilities->JSONResponseDeleted($responseArray);
    }

    // GET (by identifier)
    #[Route("/api/produits/{id}", name: 'apiGetProduitById', methods: 'GET')]
    public function getProduitById(int $id){
        $produit = $this->produitsRepository->findOneById($id);
        if(is_null($produit)){
            return $this->apiUtilities->NotFoundResponse("Aucun produit avec cet identifiant trouv??");
        }
        $data = $this->apiUtilities->formatData($produit);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "Le produit a bien ??t?? r??cup??r??", 'api:membersCount'=>1,'api:members' => $data ); 
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
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "La liste des produits a bien ??t?? envoy??e", 'api:membersCount'=>count($produits),'api:members' => $data ); 
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }

    // GET
    #[Route("/api/produits", name: 'apiGetProduits', methods: 'GET')]
    public function getAllProduits(){
        $produits = $this->produitsRepository->findBy(array(), array('id'=>'ASC'));
        if(empty($produits)){
            return $this->apiUtilities->EmptyResponse("La liste est vide!");
        }
        $data = $this->apiUtilities->formatDataArray($produits);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "La liste des produits a bien ??t?? envoy??e", 'api:membersCount'=>count($produits),'api:members' => $data ); 
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }
}