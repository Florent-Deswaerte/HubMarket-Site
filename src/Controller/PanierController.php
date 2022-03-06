<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Produits;
use App\Entity\Commandes;
use App\Manager\ProduitsManager;
use App\Repository\PanierRepository;
use App\Repository\CommandesRepository;
use App\Repository\LCommandesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\HelperControllers\HelperController;
use App\Controller\ApiControllers\Produits\ProduitsApiController;
use App\Entity\LCommandes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    public function __construct(private ProduitsApiController $produitsApiController, private PanierRepository $panierRepository, private HelperController $helper, private CommandesRepository $commandesRepository, private LCommandesRepository $lCommandesRepository)
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
        $lcommande = $this->lCommandesRepository->findOneByCommande($commande->getId());
        //Si pas connecté alors redirigé sur la page login
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        return $this->render('panier/details.html.twig', [
            'utilisateur' => $this->getUser(),
            'commande' => $commande,
            'lcommande' => $lcommande
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
                return $this->redirectToRoute('panier_historique', [
                    'commande' => $commande,
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

        return $this->render('panier/historique.html.twig', [
            'utilisateur' => $this->getUser(),
            //Trouve les commandes différentes de null en status
            'commandes' => $this->commandesRepository->findOneByStatusDifferentNull($this->getUser()->getId()),
            //Calcul la somme de toutes les commandes
            'somme' => $this->commandesRepository->findSomme($this->getUser()->getId())
        ]);
    }

    #[Route('/paiement_commande', name: 'paiement_commande')]
    public function panierPaiementCommande(Request $request): Response
    {
        //Si pas connecté alors redirigé sur la page login
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $utilisateur = $this->getUser();
        $utilisateurID = $utilisateur->getId();
        $commandeStatus = $this->commandesRepository->findOneByStatus($utilisateurID);
        if(isset($_POST['btnCommande'])){
            $total = $_POST['valueTotal'];
            if ($commandeStatus){
                //Je modifie la commande existante avec de nouvelles données
                $date = new \DateTime();
                $dateArrivee = new \DateTime();
                //J'ajoute 1 mois à la date d'arrivée
                $dateArrivee->modify('+1 month');
                $commandeStatus->setUtilisateurs($utilisateur);
                $commandeStatus->setDateCommande($date);
                $commandeStatus->setDateArrivee($dateArrivee);
                // =======================================================================> ERROR
                $commandeStatus->setTotalCommande(1515);
                $commandeStatus->setStripeToken(null);
                $commandeStatus->setBrandStripe(null);
                $commandeStatus->setLast4Stripe(null);
                $commandeStatus->setIdChargeStripe(null);

                //Je sauvegarde la commande
                $this->helper->saveEntityObject($commandeStatus);
                //Je set l'utilisateur à la commande
                $utilisateur->addCommande($commandeStatus);
                //Je sauvegarde l'utilisateur
                $this->helper->saveEntityObject($utilisateur);

                //Je créer des lignes commandes contenant les produits de la commande
                $produits = $this->getUser()->getPanier()->getProduits();
                foreach ($produits as $produit){
                    $prix = $produit->getPrix();
                    $ligneCommande = new LCommandes();
                    // =======================================================================> ERROR
                    $ligneCommande->setQty(15);
                    $ligneCommande->setPrix($prix);
                    $ligneCommande->setCommandes($commandeStatus);
                    $ligneCommande->setProduits($produit);
                    //Je sauvegarde la ligne commande
                    $this->helper->saveEntityObject($ligneCommande);
                }
                return $this->redirectToRoute('panier_details', [
                    'id' => $commandeStatus->getId(),
                ]);
            } else {
                //Je créer une commande avec de nouvelles données
                $date = new \DateTime();
                $dateArrivee = new \DateTime();
                //J'ajoute 1 mois à la date d'arrivée
                $dateArrivee->modify('+1 month');
                $commande = new Commandes();
                $commande->setUtilisateurs($utilisateur);
                $commande->setDateCommande($date);
                $commande->setDateArrivee($dateArrivee);
                // =======================================================================> ERROR
                $commande->setTotalCommande(1515);

                //Je sauvegarde la commande
                $this->helper->saveEntityObject($commande);
                //Je set l'utilisateur à la commande
                $utilisateur->addCommande($commande);
                //Je sauvegarde l'utilisateur
                $this->helper->saveEntityObject($utilisateur);

                //Je créer des lignes commandes contenant les produits de la commande
                $produits = $this->getUser()->getPanier()->getProduits();
                foreach ($produits as $produit){
                    $prix = $produit->getPrix();
                    $ligneCommande = new LCommandes();
                    // =======================================================================> ERROR
                    $ligneCommande->setQty(15);
                    $ligneCommande->setPrix($prix);
                    $ligneCommande->setCommandes($commande);
                    $ligneCommande->setProduits($produit);
                    //Je sauvegarde la ligne commande
                    $this->helper->saveEntityObject($ligneCommande);
                }
                return $this->redirectToRoute('panier_details', [
                    'id' => $commande->getId(),
                ]);
            }
        };
    }

    #[Route('/paiement_validation_commande', name: 'paiement_validation')]
    public function panierPaiementValidationCommande(Request $request): Response
    {
        //Si pas connecté alors redirigé sur la page login
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $utilisateur = $this->getUser();
        $commandeStatus = $this->commandesRepository->findOneByStatus($utilisateurID);
        if(isset($_POST['btnValidationCommande'])){
            return $this->redirectToRoute('panier_paiement', [
                'id' => $commande->getId(),
            ]);
        };
    }
}
