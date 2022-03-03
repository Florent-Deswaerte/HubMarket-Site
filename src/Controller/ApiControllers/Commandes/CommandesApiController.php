<?php
namespace App\Controller\ApiControllers\Commandes;

use App\Entity\Panier;
use App\Entity\Produits;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProduitsRepository;
use App\Controller\ApiControllers\APIUtilities;
use App\Controller\HelperControllers\HelperController;
use App\Entity\Commandes;
use App\Entity\LCommandes;
use App\Repository\CommandesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class CommandesApiController extends AbstractController
{
    public function __construct(private CommandesRepository $commandesRepository, private ProduitsRepository $produitsRepository, private APIUtilities $apiUtilities, private HelperController $helper, private EntityManagerInterface $entityManager) {}

    // POST
    #[Route("/api/commandes", name: 'apiPostCommandes', methods: 'POST')]
    public function postCommande() {
        //Je récupère l'utilisateur
        $currentUser = $this->getUser();
        //Récupération de l'id de l'utilisateur
        $user_id = $currentUser->getId();
        //Récupération de la date de début de la commande
        $date = new \DateTime();
        //Je créer une nouvelle commande
        $order = new Commandes();

        //Insertion de l'id utilisateur
        $order->setUtilisateurs($currentUser);
        //Insertion date de la commande
        $order->setDateCommande($date);
        //Insertion du total de la commande
        $order->setTotalCommande(1515);
        
        //Je sauvegarde le panier
        $this->helper->saveEntityObject($order);
        //Je set l'utilisateur au panier
        $currentUser->addCommande($order);
        //Je sauvegarde l'utilisateur
        $this->helper->saveEntityObject($currentUser);

        $data = $this->apiUtilities->formatData($order);
        $responseArray = array('api:responseCode'=>201, 'api:responseInfo' => "La commande a bien été créé", 'api:membersCount'=>1,'api:members' => $data ); 
        //Je renvoie la réponse
        return $this->apiUtilities->JSONResponseCreated($responseArray);
    }

    // GET
    #[Route("/api/commandes/get", name: 'apiGetCommandes', methods: 'GET')]
    public function getAllCommande(){
        //Je récupère une liste par id de tous les paniers
        $order = $this->commandesRepository->findBy(array(), array('id'=>'ASC'));
        if(empty($order)){
            return $this->apiUtilities->NotFoundResponse("La liste est vide!");
        }
        $data = $this->apiUtilities->formatDataArray($order);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "La liste des commandes a bien été envoyée", 'api:membersCount'=>count($order),'api:members' => $data ); 
        //Je renvoie la réponse
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }

    //DELETE COMMANDE
    #[Route("/api/commandes/{id}", name: 'apiDeleteCommandes')]
    public function deleteCommande(int $id){
        //Je récupère le panier avec le même id que l'url
        $order = $this->commandesRepository->findOneById($id);
        if(is_null($order)){
            return $this->apiUtilities->NotFoundResponse("Aucune commande avec cet identifiant trouvé");
        }
        $data = $this->apiUtilities->formatData($order);
        $responseArray = array('api:responseCode'=>204, 'api:responseInfo' => "La commande a bien été supprimé", 'api:membersCount'=>1,'api:members' => $data ); 
        //Je supprime le panier
        $this->utilities->removeEntityObject($order);
        //Je renvoie la réponse
        return $this->apiUtilities->JSONResponseDeleted($responseArray);
    }

    //POST PRODUIT DE LA COMMANDE
    #[Route("/api/commandes/add/produits/{id}/{qty}", name: 'apiAddCommandesProduit', methods: 'POST')]
    public function postCommandeProduit($id, $qty){
        //Je récupère l'utilisateur
        $currentUser = $this->getUser();
        //Récupération de l'id de l'utilisateur
        $user_id = $currentUser->getId();
        //Je récupère la commande de l'utilisateur
        $order = $this->commandesRepository->findOneByStatus($user_id);
        $order->getId();
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
        $prix = $produits->getPrix();

        //Je créer une ligne commande
        $ligneCommande = new LCommandes();
        $ligneCommande->setQty($qty);
        $ligneCommande->setPrix($prix);
        $ligneCommande->setCommandes($order);
        //J'ajoute le produit à la commande
        $ligneCommande->setProduits($produits);


        //Je sauvegarde le panier
        $this->helper->saveEntityObject($ligneCommande);
        $data = $this->apiUtilities->formatData($ligneCommande);
        $responseArray = array('api:responseCode'=>204, 'api:responseInfo' => "Le produit a bien été ajouté", 'api:membersCount'=>1,'api:members' => $data ); 
        //Je push les données
        $this->entityManager->flush();
        //Je renvoie la réponse
        return $this->apiUtilities->JSONResponseDeleted($responseArray);
    }


}