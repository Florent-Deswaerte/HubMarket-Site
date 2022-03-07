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
}