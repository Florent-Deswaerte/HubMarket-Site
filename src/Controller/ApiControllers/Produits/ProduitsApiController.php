<?php
namespace App\Controller\ApiControllers\Produits;

use App\Entity\Produits;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\ApiControllers\APIUtilities;
use App\Controller\UtilitiesControllers\UtilityController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class ProduitsApiController extends AbstractController
{
    public function __construct(private ProduitsRepository $produitsRepository, private APIUtilities $apiUtilities, private UtilityController $utilities)
    {}

    // POST
    public function postProduit(){

    }

    // PATCH
    public function patchProduit(){

    }

    //DELETE
    public function deleteProduit(){
        
    }

    // GET (by identifier)
    public function getProduitById(){

    }

    // GET
    public function getAllProduit(){

    }
}