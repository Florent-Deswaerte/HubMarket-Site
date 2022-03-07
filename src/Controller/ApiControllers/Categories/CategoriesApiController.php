<?php
namespace App\Controller\ApiControllers\Categories;

use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\ApiControllers\APIUtilities;
use App\Controller\HelperControllers\HelperController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class CategoriesApiController extends AbstractController
{
    public function __construct(private CategoriesRepository $categoriesRepository, private APIUtilities $apiUtilities, private HelperController $helper)
    {}

        // GET
        #[Route("/api/categories", name: 'apiGetCategories', methods: 'GET')]
        public function getAllCategories(){
            $categories = $this->categoriesRepository->findBy(array(), array('id'=>'ASC'));
            if(empty($categories)){
                return $this->apiUtilities->EmptyResponse("La liste est vide!");
            }
            $data = $this->apiUtilities->formatDataArray($categories);
            $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "La liste des catégories a bien été envoyée", 'api:membersCount'=>count($categories),'api:members' => $data ); 
            return $this->apiUtilities->JSONResponseOk($responseArray);
        }
}