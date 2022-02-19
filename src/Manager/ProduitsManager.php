<?php
namespace App\Manager;

use App\Entity\Commandes;
use App\Entity\Produits;
use App\Entity\Utilisateur;
use App\Services\StripeService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ProduitsManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, StripeService $stripeService)
    {
        $this->em = $entityManager;
        $this->$stripeService = $stripeService;
    }

    //Récupère tous les produits
    public function getProduits()
    {
        return $this->em->getRepository(Produits::class)->findAll();
    }
    
    //Relier au Controller, pour ne pas relier le service
    public function intentSecret(Produits $produits)
    {
        $intent = $this->stripeService->paymentIntent($produits);
        //Retourne un client secret sinon null
        return $intent['client_secret'] ?? null;
    }

    public function stripe(array $stripeParameter, Produits $produits)
    {
        $ressource = null;
        $data = $this->stripeService->stripe($stripeParameter, $produits);
        //Si data n'est pas vide
        if($data){
            //Récupérer les informations stripe
            $ressource = [
                /**
                 * Dans chaque data il y a une charges de paiement
                 * Dans chaque charges il y a une data
                 * Et dans data une clé 0 pour récupérer tout ce que l'on souhaite
                 */
                'stripeBrand' => $data['charges']['data'][0]['payment_method_details']['card']['brand'],
                //Récupérer les 4 derniers chiffre
                'stripeLast4' => $data['charges']['data'][0]['payment_method_details']['card']['last4'],
                //Récupérer l'id du paiement stripe
                'stripeId' => $data['charges']['data'][0]['id'],
                //Récupérer le status du paiement stripe
                'stripeStatus' => $data['charges']['data'][0]['status'],
                //Token retourné par stripe
                'stripeToken' => $data['client_secret']
            ];
        }
        return $ressource;
    }

    //Créer une commande
    public function create_subscription(array $ressource, Produits $produis, Utilisateur $user)
    {
        //Récupération de l'id de l'utilisateur
        $user_id = $user->getId();
        //Récupération de la date de début de la commande
        $date = new \DateTime();
        //Création commande
        $order = new Commandes();
        //Insertion de l'id utilisateur
        $order->setUtilisateurs($user_id);
        //Insertion date de la commande
        $order->setDateCommande($date);
        //Insertion du total de la commande
        $order->setTotalCommande(1515);
        //Insertion ressource brand de Stripe
        $order->setBrandStripe($ressource['stripeBrand']);
        //Insertion ressource last4 de Stripe
        $order->setLast4Stripe($ressource['stripeLast4']);
        //Insertion ressource id de Stripe
        $order->setIdChargeStripe($ressource['stripeId']);
        //Insertion ressource status de Stripe
        $order->setStatusStripe($ressource['stripeStatus']);
        //Insertion ressource token de Stripe
        $order->setStripeToken($ressource['stripeToken']);

        $this->em->persist($order);
        $this->em->flush();
    }
}
?>