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
        $userData = $this->user->getData();
        $payload['id'] = $userData['id'];
        $payload['adresse'] = $userData['adresse'];
        $payload['nom'] = $userData['nom'];
        $payload['prenom'] = $userData['prenom'];
        $payload['phone'] = $userData['phone'];
        $payload['pays'] = $userData['pays'];
        $payload['ville'] = $userData['ville'];
        $payload['panier'] = $userData['panier'];
        $payload['commandes'] = $userData['commandes'];

        $event->setData($payload);

        $header = $event->getHeader();
        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }
}