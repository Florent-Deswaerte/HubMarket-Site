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
    #[Route("/api/produits/post", name: 'apiPostProduit', methods: 'POST')]
    public function postProduit(){

    }

    // PATCH
    #[Route("/api/produits/patch/{id}", name: 'apiPatchProduit', methods: 'PATCH')]
    public function patchProduit(int $id){

    }

    //DELETE
    #[Route("/api/produits/delete/{id}", name: 'apiDeleteProduit', methods: 'DELETE')]
    public function deleteProduit(int $id){

    }

    // GET (by identifier)
    #[Route("/api/produits/get/id/{id}", name: 'apiGetProduitById', methods: 'GET')]
    public function getProduitById(int $id){

    }

    // GET (by name)
    #[Route("/api/produits/get/name/{name}", name: 'apiGetProduitByName', methods: 'GET')]
    public function getProduitByName(string $name){

    }

    // GET
    #[Route("/api/produits/get", name: 'apiGetProduits', methods: 'GET')]
    public function getAllProduit(){

    }
}