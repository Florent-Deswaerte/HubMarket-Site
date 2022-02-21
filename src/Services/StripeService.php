<?php
namespace App\Services;

use App\Entity\Commandes;
use App\Entity\Produits;

class StripeService
{
    //Constructeur
    private $privateKey;
    public function __construct()
    {
        //Si environnement dev alors clé test sinon clé réel
        if($_ENV['APP_ENV'] === 'dev'){
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        }else{
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_LIVE'];
        }
    }

    //Associer prix produit à une clé
    /** 
     * @param Produits $produits
     * @return \Stripe\PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
    */
    public function paymentIntent(Commandes $commandes)
    {
        //Clé privé
        \Stripe\Stripe::setApiKey($this->privateKey);

        dd(\Stripe\PaymentIntent::create([
            //Prix du produits multiplié par 100 pour avoir les bonnes conversions
            'amount' => $commandes->getTotalCommande() * 100,
            //Devise
            'currency' => 'eur',
            //Type de paiement
            'payment_method_types' => ['card']
        ]));
    }

    //Déclencher le paiement
    public function paiement($amount, $currency, $description, array $stripeParameter)
    {
        //Clé privé
        \Stripe\Stripe::setApiKey($this->privateKey);
        $payment_intent = null;

        //Si l'id intent stripe existe
        if(isset($stripeParameter['stripeIntentId'])){
            //Retoune $payment_intent
            $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
        }
        //Si le paiement est validé
        if($stripeParameter['stripeIntentId'] === 'succeeded'){
            //TODO
        }else{
            //Annuler le paiement
            $payment_intent->cancel();
        }
        //Retourne paiement validé
        return $payment_intent;
    }

    //Déclenche le paiement stripe
    /**
     * @param array $stripeParameter
     * @param Produits $produits
     * @return \Stripe\PaymentIntent|null
     */
    public function stripe(array $stripeParameter, Commandes $commandes){
        return $this->paiement(
            $commandes->getTotalCommande() * 100,
            'eur',
            $commandes->getId(),
            $stripeParameter
        );
    }
}
?>