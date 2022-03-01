<?php

namespace App\Controller\HelperControllers;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;

class HelperController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function redirectToWithFlashMesage(string $routeName, string $message, string $messageType, array $parameters): Response {
        $this->addFlash($messageType, $message);
        return $this->redirectToRoute($routeName, $parameters);
    }

    public function saveEntityObject($entityObj) {
        $this->entityManager->persist($entityObj);
        $this->entityManager->flush();
    }

    public function removeEntityObject($entityObj) {
        $this->entityManager->remove($entityObj);
        $this->entityManager->flush();
    }

    public function arrayFindBy($arr, $property, $value) {
        $item = null;
        foreach($arr as $struct) {
            if ($value == $struct[$property]) {
                $item = $struct;
                break;
            }
        }
        return $item;
    }
}