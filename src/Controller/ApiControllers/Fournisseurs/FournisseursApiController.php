<?php
namespace App\Controller\ApiControllers\Fournisseurs;

use App\Entity\Fournisseurs;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FournisseursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\ApiControllers\APIUtilities;
use App\Controller\HelperControllers\HelperController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class FournisseursApiController extends AbstractController
{
    public function __construct(private FournisseursRepository $fournisseursRepository, private APIUtilities $apiUtilities, private HelperController $helper)
    {}

    // GET
    #[Route("/api/fournisseurs", name: 'apiGetFournisseurs', methods: 'GET')]
    public function getAllFournisseurs(){
        $fournisseurs = $this->fournisseursRepository->findBy(array(), array('id'=>'ASC'));
        if(empty($fournisseurs)){
            return $this->apiUtilities->EmptyResponse("La liste est vide!");
        }
        $data = $this->apiUtilities->formatDataArray($fournisseurs);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "La liste des fournisseurs a bien été envoyée", 'api:membersCount'=>count($fournisseurs),'api:members' => $data );
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }

    // GET (by libelle)
    #[Route("/api/fournisseurs/libelle/{libelle}", name: 'apiGetFournisseurByName', methods: 'GET')]
    public function getFournisseurByLibelle(string $libelle){
        $fournisseur = $this->fournisseursRepository->findO($libelle);
        if(empty($fournisseur)){
            return $this->apiUtilities->NotFoundResponse("La liste est vide!");
        }
        $data = $this->apiUtilities->formatDataArray($fournisseur);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "La liste des fournisseurs a bien été envoyée", 'api:membersCount'=>count($fournisseur),'api:members' => $data ); 
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }

    // POST
    #[Route("/api/fournisseur", name: 'apiPostFournisseur', methods: 'POST')]
    public function postFournisseur(Request $request){
        $fournisseur = new Fournisseurs(); //
        $libelleFournisseur = $request->get('libelle');
        $foundFournisseur = $this->fournisseursRepository->findOneByLibelle($libelleFournisseur);
        if(!is_null($foundFournisseur)) {
            return $this->apiUtilities->BadRequestResponse("Un fournisseur possédant ce libelle existe déjà!");
        }
        else {
            $fournisseur->setLibelle($libelleFournisseur);
            $this->helper->saveEntityObject($fournisseur);
            $data = $this->apiUtilities->formatData($fournisseur);
            $responseArray = array('api:responseCode'=>Response::HTTP_CREATED, 'api:responseInfo' => "Le fournisseur a bien été créé", 'api:membersCount'=>1,'api:members' => $data ); 
            return $this->apiUtilities->JSONResponseCreated($responseArray);            
        }
    }

    // PATCH
    #[Route("/api/fournisseur/{id}", name: 'apiPatchFournisseur', methods: 'POST')]
    public function patchFournisseur(Request $request, int $id){
        $libelleFournisseur = $request->get('libelle');
        $oldFournisseur = $this->fournisseursRepository->findOneById($id);
        $foundFournisseur = $this->fournisseursRepository->findOneByLibelle($libelleFournisseur);
        if(!is_null($oldFournisseur)) {
            if(is_null($foundFournisseur)) {
                $oldFournisseur->setLibelle($libelleFournisseur);
                $this->helper->saveEntityObject($oldFournisseur);
                $data = $this->apiUtilities->formatData($oldFournisseur);
                $responseArray = array('api:responseCode'=>Response::HTTP_OK, 'api:responseInfo' => "Le fournisseur a bien été modifié", 'api:membersCount'=>1,'api:members' => $data ); 
                return $this->apiUtilities->JSONResponseCreated($responseArray);  
            }
            else{
                return $this->apiUtilities->BadRequestResponse("Un fournisseur possédant ce libelle existe déjà");
            }
        }
        else{
            return $this->apiUtilities->NotFoundResponse("Aucun fournisseur trouvé!");
        }
    }

    //DELETE
    #[Route("/api/fournisseurs/{id}", name: 'apiDeleteFournisseur', methods: 'DELETE')]
    public function deleteFournisseur(int $id){
        $fournisseur = $this->fournisseursRepository->findOneById($id);
        if(is_null($fournisseur)){
            return $this->apiUtilities->NotFoundResponse("Aucun fournisseur avec cet identifiant trouvé");
        }
        $data = $this->apiUtilities->formatData($fournisseur);
        $responseArray = array('api:responseCode'=>204, 'api:responseInfo' => "Le fournisseur a bien été supprimé", 'api:membersCount'=>1,'api:members' => $data ); 
        $this->utilities->removeEntityObject($fournisseur);
        return $this->apiUtilities->JSONResponseDeleted($responseArray);
    }
}