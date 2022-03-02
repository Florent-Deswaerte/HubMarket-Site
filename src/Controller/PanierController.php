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
    #[Route('/details/{id}', name: 'details')]
    public function panierDetails(int $id): Response
    {
        //Cherche la commande avec cette id
        $commande = $this->commandesRepository->findOneById($id);
        //Si pas connecté alors redirigé sur la page login
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $utilisateur1 = $this->getUser();
        $utilisateur = $utilisateur1->getData();
        //dd($utilisateur);
        return $this->render('panier/details.html.twig', [
            'utilisateur' => $utilisateur,
            'commande' => $commande
        ]);
    }

    //Page du paiement
    #[Route('/paiement/{id}', name: 'paiement')]
    public function panierPaiement(int $id, ProduitsManager $produitsManager): Response
    {
        //Cherche la commande avec cette id
        $commande = $this->commandesRepository->findOneById($id);
        //Si pas connecté alors redirigé sur la page login
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        //Récupération information utilisateur
        $utilisateur = $this->getUser()->getData();
        
        //Récupération de la commande
        $intent_secret = $produitsManager->intentSecret($commande);

        return $this->render('panier/paiement.html.twig', [
            'utilisateur' => $utilisateur,
            'commande' => $commande,
            'intentSecret' => $intent_secret
        ]);
    }

    //Action formulaire
    #[Route('/subscription/{id}', name: 'subscription_paiement')]
    public function panierSubscription(int $id, Request $request, ProduitsManager $produitsManager): Response
    {
        $commande = $this->commandesRepository->findById($id);
        //Récupérer le premier indice du tableau $commande pour avoir juste la commande que l'on veut
        $order = $commande[0];
        //Si pas connecté alors redirigé sur la page login
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        //Récupération information utilisateur
        $utilisateur = $this->getUser();
        if($request->getMethod()=== "POST"){
            //Retourner la ressource et faire le traitement
            $ressource = $produitsManager->stripe($request->request->all(), $order);
            //Si ressource différent de null
            if(null!= $ressource){
                //Créer la commande
                $produitsManager->create_subscription($ressource, $order, $utilisateur);
                //Retourne une réponse
                return $this->render('panier/reponse.html.twig', [
                    'commande'=>$commande
                ]);
            }
        }

        return $this->redirectToRoute('panier_paiement', ['id'=> $id]);
    }

    //Page du récapitulatif des commandes
    #[Route('/historique', name: 'historique')]
    public function historique(ProduitsManager $produitsManager): Response
    {
        //Si pas connecté alors redirigé sur la page login
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        //$lcommande = $this->commandesRepository->findOneByCommande($this->getUser()->getId());

        return $this->render('panier/historique.html.twig', [
            'utilisateur' => $this->getUser(),
            'commande' => $this->commandesRepository->findOneByStatusDifferentNull($this->getUser()->getId()),
            'somme' => $this->commandesRepository->findSomme($this->getUser()->getId())
        ]);
    }
}
