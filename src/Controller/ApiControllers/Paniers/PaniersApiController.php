<?php
namespace App\Controller\ApiControllers\Paniers;

use App\Entity\Panier;
use App\Entity\Produits;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProduitsRepository;
use App\Controller\ApiControllers\APIUtilities;
use App\Controller\HelperControllers\HelperController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class PaniersApiController extends AbstractController
{
    public function __construct(private PanierRepository $panierRepository, private ProduitsRepository $produitsRepository, private APIUtilities $apiUtilities, private HelperController $helper, private EntityManagerInterface $entityManager) {}

    // POST
    #[Route("/api/panier", name: 'apiPostPanier', methods: 'POST')]
    public function postPanier() {
        //Je récupère l'utilisateur
        $currentUser = $this->getUser();
        //Je créer un nouveau panier
        $panier = new Panier();
        //Je sauvegarde le panier
        $this->helper->saveEntityObject($panier);
        //Je set l'utilisateur au panier
        $currentUser->setPanier($panier);
        //Je sauvegarde l'utilisateur
        $this->helper->saveEntityObject($currentUser);

        $data = $this->apiUtilities->formatData($panier);
        $responseArray = array('api:responseCode'=>201, 'api:responseInfo' => "Le panier a bien été créé", 'api:membersCount'=>1,'api:members' => $data ); 
        //Je renvoie la réponse
        return $this->apiUtilities->JSONResponseCreated($responseArray);
    }

    // GET
    #[Route("/api/panier", name: 'apiGetPanier', methods: 'GET')]
    public function getAllPanier(){
        //Je récupère une liste par id de tous les paniers
        $panier = $this->panierRepository->findBy(array(), array('id'=>'ASC'));
        if(empty($panier)){
            return $this->apiUtilities->NotFoundResponse("La liste est vide!");
        }
        $data = $this->apiUtilities->formatDataArray($panier);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "La liste des panier a bien été envoyée", 'api:membersCount'=>count($panier),'api:members' => $data ); 
        //Je renvoie la réponse
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }

    //DELETE PANIER
    #[Route("/api/panier/{id}", name: 'apiDeletePanier', methods: 'DELETE')]
    public function deletePanier(int $id){
        //Je récupère le panier avec le même id que l'url
        $panier = $this->panierRepository->findOneById($id);
        if(is_null($panier)){
            return $this->apiUtilities->NotFoundResponse("Aucun panier avec cet identifiant trouvé");
        }
        $data = $this->apiUtilities->formatData($panier);
        $responseArray = array('api:responseCode'=>204, 'api:responseInfo' => "Le panier a bien été supprimé", 'api:membersCount'=>1,'api:members' => $data ); 
        //Je supprime le panier
        $this->utilities->removeEntityObject($panier);
        //Je renvoie la réponse
        return $this->apiUtilities->JSONResponseDeleted($responseArray);
    }

    //DELETE PRODUIT DU PANIER
    #[Route("/api/panier/delete/produits/{id}", name: 'apiDeletePanierProduit', methods: 'DELETE')]
    public function deletePanierProduit(int $id){
        //Je récupère le panier de l'utilisateur
        $panier = $this->getUser()->getPanier();
        //Je récupère les produits du panier
        $panierProduits = $panier->getProduits();
        //Je recherche un produits avec le même id que dans l'url dans le panier
        $panierProduits = $this->produitsRepository->findOneById($id);
        if(is_null($panierProduits)){
            return $this->apiUtilities->NotFoundResponse("Aucun produit avec cet identifiant trouvé");
        }
        //Je supprime le produit du panier
        $panier->removeProduit($panierProduits);
        //Je sauvegarde le panier
        $this->helper->saveEntityObject($panier);
        $data = $this->apiUtilities->formatData($panierProduits);
        $responseArray = array('api:responseCode'=>204, 'api:responseInfo' => "Le produit a bien été supprimé", 'api:membersCount'=>1,'api:members' => $data ); 
        //Je push les données
        $this->entityManager->flush();
        //Je renvoie la réponse
        return $this->apiUtilities->JSONResponseDeleted($responseArray);
    }

    //POST PRODUIT DU PANIER
    #[Route("/api/panier/add/produits/{id}", name: 'apiAddPanierProduit', methods: 'POST')]
    public function postPanierProduit(int $id){
        //Je récupère le panier de l'utilisateur
        $panier = $this->getUser()->getPanier();
        //Je récupère une list par id de tous les produits existants
        $produits = $this->produitsRepository->findBy(array(), array('id'=>'ASC'));
        if(empty($produits)){
            return $this->apiUtilities->NotFoundResponse("La liste des produits est vide!");
        }
        //Je recherche un produits avec le même id que dans l'url dans la liste précédente
        $produits = $this->produitsRepository->findOneById($id);
        if(is_null($produits)){
            return $this->apiUtilities->NotFoundResponse("Aucun produit avec cet identifiant trouvé");
        }
        //J'ajoute le produit au panier
        $panier->addProduit($produits);
        //Je sauvegarde le panier
        $this->helper->saveEntityObject($panier);
        $data = $this->apiUtilities->formatData($produits);
        $responseArray = array('api:responseCode'=>204, 'api:responseInfo' => "Le produit a bien été ajouté", 'api:membersCount'=>1,'api:members' => $data ); 
        //Je push les données
        $this->entityManager->flush();
        //Je renvoie la réponse
        return $this->apiUtilities->JSONResponseDeleted($responseArray);
    }
}