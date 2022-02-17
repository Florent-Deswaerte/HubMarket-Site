<?php
namespace App\Controller\ApiControllers\Paniers;

use App\Entity\Panier;
use App\Repository\PanierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\ApiControllers\APIUtilities;
use App\Controller\HelperControllers\HelperController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class PaniersApiController extends AbstractController
{
    public function __construct(private PanierRepository $panierRepository, private APIUtilities $apiUtilities, private HelperController $helper) {}

    // POST
    #[Route("/api/panier", name: 'apiPostPanier')]
    public function postPanier() {
        $currentUser = $this->getUser();
        $panier = new Panier();
        $this->helper->saveEntityObject($panier);
        $currentUser->setPanier($panier);
        $this->helper->saveEntityObject($currentUser);
        dd($currentUser);

        $data = $this->apiUtilities->formatData($panier);
        $responseArray = array('api:responseCode'=>201, 'api:responseInfo' => "Le panier a bien été créé", 'api:membersCount'=>1,'api:members' => $data ); 
        return $this->apiUtilities->JSONResponseCreated($responseArray);
    }

    // GET
    #[Route("/api/panier", name: 'apiGetPanier', methods: 'GET')]
    public function getAllPanier(){
        $panier = $this->panierRepository->findBy(array(), array('id'=>'ASC'));
        if(empty($panier)){
            return $this->apiUtilities->NotFoundResponse("La liste est vide!");
        }
        $data = $this->apiUtilities->formatDataArray($panier);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "La liste des panier a bien été envoyée", 'api:membersCount'=>count($panier),'api:members' => $data ); 
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }

    //DELETE
    #[Route("/api/panier/{id}", name: 'apiDeletePanier', methods: 'DELETE')]
    public function deletePanier(int $id){
        $panier = $this->panierRepository->findOneById($id);
        if(is_null($panier)){
            return $this->apiUtilities->NotFoundResponse("Aucun panier avec cet identifiant trouvé");
        }
        $data = $this->apiUtilities->formatData($panier);
        $responseArray = array('api:responseCode'=>204, 'api:responseInfo' => "Le panier a bien été supprimé", 'api:membersCount'=>1,'api:members' => $data ); 
        $this->utilities->removeEntityObject($panier);
        return $this->apiUtilities->JSONResponseDeleted($responseArray);
    }
}