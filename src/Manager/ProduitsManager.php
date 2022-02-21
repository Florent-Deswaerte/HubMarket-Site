<?php
namespace App\Manager;

use App\Entity\Commandes;
use App\Entity\Produits;
use App\Entity\Utilisateur;
use App\Repository\CommandesRepository;
use App\Services\StripeService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ProduitsManager
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private EntityManagerInterface $entityManager, private StripeService $stripeService, private CommandesRepository $commandesRepository)
    {}

    //Récupère tous les produits
    public function getProduits()
    {
        return $this->em->getRepository(Produits::class)->findAll();
    }
    
    //Relier au Controller, pour ne pas relier le service
    public function intentSecret(Commandes $commandes)
    {
        $intent = $this->stripeService->paymentIntent($commandes);
        //Retourne un client secret sinon null
        return $intent['client_secret'] ?? null;
    }

    public function stripe(array $stripeParameter, Commandes $commandes)
    {
        $ressource = null;
        $data = $this->stripeService->stripe($stripeParameter, $commandes);
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
    public function create_subscription(array $ressource, Commandes $commandes, Utilisateur $user)
    {
        //Récupération de l'id de l'utilisateur
        $user_id = $user->getId();
        //Je récupère la commande de l'utilisateur
        $order = $this->commandesRepository->findOneByStatus($user_id);

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