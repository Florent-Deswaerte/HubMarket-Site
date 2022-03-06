<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Utilisateurs;
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
use App\Form\InformationPaiementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    public function __construct(
        private ProduitsApiController $produitsApiController,
        private PanierRepository $panierRepository,
        private HelperController $helper,
        private CommandesRepository $commandesRepository,
        private LCommandesRepository $lCommandesRepository,
        private EntityManagerInterface $entityManager)
    {}

    //Page du récapitulatif du panier de l'utilisateur
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

    //Page contenant les informations personnelles de l'utilisateur
    #[Route('/details/{id}', name: 'details')]
    public function panierDetails(int $id, Request $request): Response
    {
        //Cherche la commande avec cette id
        $commande = $this->commandesRepository->findOneById($id);
        $lcommande = $this->lCommandesRepository->findOneByCommande($commande->getId());

        //Initialise le Formulaire avec les données de l'utilisateurs
        $form = $this->createForm(InformationPaiementType::class, $this->getUser());
        //Récupère les données du Formulaire et rempli l'utilisateur
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $this->entityManager->flush();
            return $this->redirectToRoute('panier_paiement', [
                'id' => $commande->getId(),
            ]);
        }
        
        return $this->render('panier/details.html.twig', [
            'utilisateur' => $this->getUser(),
            'form' => $form->createView(),
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

        //Récupération information utilisateur
        
        /** @var Utilisateurs $utilisateur 
         * Pour pouvoir cliquer sur setPanier() (pour dire quel object c'est)
        */
        $utilisateur = $this->getUser();
        if($request->getMethod()=== "POST"){
            //Retourner la ressource et faire le traitement
            $ressource = $produitsManager->stripe($request->request->all(), $order);
            //Si ressource différent de null
            if(null!= $ressource){
                //Créer la commande
                $produitsManager->create_subscription($ressource, $order, $utilisateur);
                //Retourne une réponse
                $panier = $utilisateur->getPanier();
                $utilisateur->setPanier(null);
                $this->entityManager->flush();
                $this->helper->removeEntityObject($panier);
                return $this->redirectToRoute('panier_historique_commande', [
                    'commande' => $commande,
                ]);
            }
        }

        return $this->redirectToRoute('panier_paiement', ['id'=> $id]);
    }

    //Page du récapitulatif des commandes
    #[Route('/historique', name: 'historique_commande')]
    public function historique(ProduitsManager $produitsManager): Response
    {
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
        // id produit & quantité
        $dataCommande = \json_decode($request->request->get('dataCommande'), true);
        $prixTotal = $request->request->get('valueTotal');

        $utilisateur = $this->getUser();
        $utilisateurID = $utilisateur->getId();
        $commandeStatus = $this->commandesRepository->findOneByStatus($utilisateurID);
        if(isset($_POST['btnCommande'])){
            if ($commandeStatus){
                //Je modifie la commande existante avec de nouvelles données
                $date = new \DateTime();
                $dateArrivee = new \DateTime();
                //J'ajoute 1 mois à la date d'arrivée
                $dateArrivee->modify('+1 month');
                $commandeStatus->setUtilisateurs($utilisateur);
                $commandeStatus->setDateCommande($date);
                $commandeStatus->setDateArrivee($dateArrivee);
                $commandeStatus->setTotalCommande($prixTotal);

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
                    $ligneCommandes = $commandeStatus->getCommandes();

                    foreach ($ligneCommandes as $ligneCommande) {
                        $ligneCommande->setQty($this->findQuantity($dataCommande, $produit->getId()));
                        $ligneCommande->setPrix($prix);
                        $ligneCommande->setCommandes($commandeStatus);
                        $ligneCommande->setProduits($produit);
                    }

                    //Je sauvegarde la ligne commande
                    $this->entityManager->flush();
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
                $commande->setTotalCommande($prixTotal);

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
                    $ligneCommande->setQty($this->findQuantity($dataCommande, $produit->getId()));
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

    public function findQuantity(array $allQuantity, int $idProduit): int
    {
       foreach ($allQuantity as $key => $value) {
           if ($key === $idProduit) { return $value; }
       }
    }
}
