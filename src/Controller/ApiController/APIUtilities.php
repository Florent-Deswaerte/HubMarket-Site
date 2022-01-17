<?php

namespace App\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

use OpenApi\Annotations as OA;

class APIUtilities extends AbstractController
{
    #[Route('/api', name: 'api')]
    public function interface(): Response
    {
        return $this->render('api/index.html', []);
    }

    public function formatData($object)
    {
        $dataArray = array();
        array_push($dataArray, $object->getData());
        return $dataArray;
    }

    public function formatDataArray(array $array) :array
    {
        $dataArray = array();
        foreach($array as $item){
            array_push($dataArray, $item->getData());
        }
        return $dataArray;
    }

    public function JSONResponse(string $jsonData) : Response
    {
        return new Response( 
            $jsonData,
            Response::HTTP_OK,
            [
                'content-type' => 'application/json',
                'charset' => "utf-8",
            ]
        );
    }

    public function NotFoundResponse(string $info) : Response
    {
        return new Response( 
            $info,
            Response::HTTP_NOT_FOUND,
        );
    }
}
?>