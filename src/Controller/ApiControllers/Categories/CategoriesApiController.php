<?php
namespace App\Controller\ApiControllers\Categories;

use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\ApiControllers\APIUtilities;
use App\Controller\UtilitiesControllers\UtilityController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class CategoriesApiController extends AbstractController
{
    public function __construct(private CategoriesRepository $categoriesRepository, private APIUtilities $apiUtilities, private UtilityController $utilities)
    {}
}