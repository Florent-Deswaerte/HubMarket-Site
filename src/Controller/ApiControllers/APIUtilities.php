<?php

namespace App\Controller\ApiControllers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class APIUtilities extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods:['POST'])]
    public function apiLogin(){
        $user = $this->getUser();
        return $this->json([
            'email' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
        ]);
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

    public function JSONResponseCreated(array $arr) : Response
    {
        $jsonData = json_encode($arr, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        return new Response( 
            $jsonData,
            Response::HTTP_CREATED,
            [
                'content-type' => 'application/json',
                'charset' => "utf-8",
            ]
        );
    }

    public function JSONResponseOk(array $arr) : Response
    {
        $jsonData = json_encode($arr, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        return new Response( 
            $jsonData,
            Response::HTTP_OK,
            [
                'content-type' => 'application/json',
                'charset' => "utf-8",
            ]
        );
    }

    public function JSONResponseDeleted(array $arr) : Response
    {
        $jsonData = json_encode($arr, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        return new Response( 
            $jsonData,
            204,
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

    public function EmptyResponse(string $info) : Response
    {
        return new Response( 
            $info,
            204
        );
    }

    public function NotAuthorizedResponse(string $info) : Response
    {
        return new Response( 
            $info,
            '401',
        );
    }

    public function BadRequestResponse(string $info): Response
    {
        return new Response(
            $info,
            Response::HTTP_BAD_REQUEST,
        );
    }
}
?>