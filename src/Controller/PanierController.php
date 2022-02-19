<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Produits;
use App\Entity\Commandes;
use App\Manager\ProduitsManager;
use App\Repository\PanierRepository;
use App\Repository\CommandesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\HelperControllers\HelperController;
use App\Controller\ApiControllers\Produits\ProduitsApiController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    public function __construct(private ProduitsApiController $produitsApiController, private PanierRepository $panierRepository, private HelperController $helper, private CommandesRepository $commandesRepository)
    {}

    //Page du récapitulatif du panier de l'utilisateur
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        //Si pas connecté alors redirigé sur la page login
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $panier = $this->getUser()->getPanier();
        //dd($panier);
        $panierProduits = $panier->getProduits();
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'panier' => $panier,
            'produits' => $panierProduits,
        ]);
    }
    //Page contenant les informations personnelles de l'utilisateur
    #[Route('/details', name: 'details')]
    public function panierDetails(): Response
    {
        //Si pas connecté alors redirigé sur la page login
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $utilisateur1 = $this->getUser();
        $utilisateur = $utilisateur1->getData();
        //dd($utilisateur);
        return $this->render('panier/details.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    //Page du paiement
    #[Route('/paiement/{id}', name: 'paiement')]
    public function panierPaiement(int $id, ProduitsManager $produitsManager): Response
    {
        $commande = $this->commandesRepository->findOneById($id);
        //Si pas connecté alors redirigé sur la page login
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        //Récupération information utilisateur
        $utilisateur = $this->getUser()->getData();
        
        //Récupération de la commande
        $intent_secret = $produitsManager->intentSecret($commande);

        return $this->render('panier/details.html.twig', [
            'utilisateur' => $utilisateur,
            'commande' => $commande,
            'intentSecret' => $intent_secret
        ]);
    }
}
