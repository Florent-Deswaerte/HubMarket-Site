<?php
namespace App\Controller\ApiControllers\Utilisateurs;

use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\ApiControllers\APIUtilities;
use App\Controller\HelperControllers\HelperController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class UserApiController extends AbstractController
{
    public function __construct(private UtilisateursRepository $userRepository, private APIUtilities $apiUtilities, private HelperController $utilities)
    {}
    
    #[Route('/api/users', name: 'apiGetUsersList', methods: 'GET')]
    public function apiGetUsersList(): Response|Utilisateurs
    {
        $users = $this->userRepository->findBy(array(), array('id'=>'ASC'));
        if(empty($users)){
            return $this->apiUtilities->NotFoundResponse("La liste est vide!");
        }
        $data = $this->apiUtilities->formatDataArray($users);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "La liste des utilisateurs a bien été envoyée", 'api:membersCount'=>count($users),'api:members' => $data ); 
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }

    #[Route("/api/users/email/{email}", name: 'apiGetUserByEmail', methods: 'GET')]
    public function apiGetUserByEmail(string $email): Response|Utilisateurs
    {
        $user = $this->userRepository->findOneByEmail($email);
        if(is_null($user)) return $this->apiUtilities->NotFoundResponse("L'utilisateur n'a pas été trouvé");
        $data = $this->apiUtilities->formatData($user);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "L'utilisateur a été trouvé", 'api:membersCount'=>1,'api:members' => $data );
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }

    #[Route("/api/users/{id}", name: 'apiGetUserById', methods: 'GET')]
    public function apiGetUserById(int $id): Response|Utilisateurs
    {
        $user = $this->userRepository->findOneByID($id);
        if(is_null($user)) return $this->apiUtilities->NotFoundResponse("L'utilisateur n'a pas été trouvé");
        $data = $this->apiUtilities->formatData($user);
        $responseArray = array('api:responseCode'=>200, 'api:responseInfo' => "L'utilisateur a été trouvé", 'api:membersCount'=>1,'api:members' => $data );
        return $this->apiUtilities->JSONResponseOk($responseArray);
    }

    #[Route("/api/users", name: 'apiCreateUser', methods: 'POST')]
    public function apiCreateUser(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response|Utilisateurs
    {
        $user = new Utilisateurs();
        $email = $_GET['email'];
        $password = $_GET['password'];
        $foundUser = $this->userRepository->findOneByEmail($email);
        if(!is_null($foundUser)) return $this->apiUtilities->NotFoundResponse("Un utilisateur avec cet email existe déjà");
        $user->setEmail($email);
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
        );
        $this->utilities->saveEntityObject($user);
        $data = $this->apiUtilities->formatData($user);
        $responseArray = array('api:responseCode'=>201, 'api:responseInfo' => "L'utilisateur a bien été créé", 'api:membersCount'=>1, 'api:members'=> $data);
        return $this->apiUtilities->JSONResponseCreated($responseArray);
    }

    #[Route("/api/users/{id}", name: 'apiDeleteUser', methods: 'DELETE')]
    public function apiDeleteUser(EntityManagerInterface $entityManager,int $id): Response|Utilisateurs 
    {
        $user = $this->userRepository->findOneByID($id);
        if(is_null($user)) return $this->apiUtilities->NotFoundResponse("L'utilisateur n'a pas été trouvé");
        $data = $this->apiUtilities->formatData($user);
        $responseArray = array('api:responseCode'=>204, 'api:responseInfo' => "L'utilisateur a bien été suppprimé", 'api:membersCount'=>1,'api:members' => $data );
        $json = $this->apiUtilities->JSONResponseDeleted($responseArray);
        $this->utilities->removeEntityObject($user);
        return $json;
    }
}
?>