<?php

namespace App\Controller;

use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produits;
use App\Entity\Panier;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\HelperControllers\HelperController;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    public function __construct(private PanierRepository $panierRepository, private HelperController $helper)
    {}

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $panier = $this->getUser()->getPanier();
        //dd($panier);
        $panierProduits = $panier->getProduits();
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'panier' => $panier,
            'produits' => $panierProduits,
        ]);
    }
}
