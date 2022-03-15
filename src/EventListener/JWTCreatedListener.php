<?php
namespace App\EventListener;

use App\Entity\Utilisateurs;
use App\Repository\UtilisateursRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener   {
    /**
     * @var RequestStack
     */
    private $requestStack;
    private Utilisateurs $user;

    /**
     * @param RequestStack $requestStack
     */
    public
    function __construct(RequestStack $requestStack, private UtilisateursRepository $utilisateursRepository) {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public
    function onJWTCreated(JWTCreatedEvent $event) {
        $request = $this->requestStack->getCurrentRequest();

        $payload = $event->getData();
        $this->user = $this->utilisateursRepository->findOneByEmail($payload['username']);
        $payload['id'] = $this->user->getId();
        $payload['adresse'] = $this->user->getAdresse();
        $payload['nom'] = $this->user->getNom();
        $payload['prenom'] = $this->user->getPrenom();
        $payload['phone'] = $this->user->getPhone();
        $payload['pays'] = $this->user->getPays();
        $payload['ville'] = $this->user->getVille();
        if(!is_null($this->user->getCommande())){
            $commandes = array();
            foreach($this->user->getCommande() as $commande){
                array_push($commandes, $commande->getData());
            }
            $payload['commandes'] = $commandes;
        }
        else{
            $payload['commandes'] = null;
        }
        if(!is_null($this->user->getPanier())){
            $payload['panier'] = $this->user->getPanier()->getData();
        }
        else{
            $payload['panier'] = null;
        }

        $event->setData($payload);

        $header = $event->getHeader();
        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }
}