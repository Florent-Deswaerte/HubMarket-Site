<?php
namespace App\Controller\ApiControllers\Fournisseurs;

use App\Entity\Fournisseurs;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FournisseursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\ApiControllers\APIUtilities;
use App\Controller\UtilitiesControllers\UtilityController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class FournisseursApiController extends AbstractController
{
    public function __construct(private FournisseursRepository $fournisseursRepository, private APIUtilities $apiUtilities, private UtilityController $utilities)
    {}
}