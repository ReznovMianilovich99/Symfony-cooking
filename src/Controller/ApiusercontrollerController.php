<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Entity\DeletableEntityInterface;

// #[Route('/apicon/user')]
final class ApiusercontrollerController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/apiuser/all',methods: "GET")]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        foreach ($users as $user) 
        {
            $userDTO[] = new UserDTO($user);
        }
        $data = $this->serializer->serialize($userDTO, 'json');
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    // #[Route('/apiuser/new', methods: 'POST')]
    // public function create(EntityManagerInterface $em , #[MapRequestPayload(serializationContext:[
    //     'groups' => ['users.create']
    // ])] User $user)
    // {
    //     dd($user);
    //     // return $this->json($user,200,[],[
    //     //     'groups' => ['users.show']
    //     // ]);
    // }

    #[Route('/apiuser/new', methods: 'POST')]
    public function create(Request $req , EntityManagerInterface $em ,UserRepository $userRepository)
    {
        $data = json_decode($req->getContent(), true);

        $use = new User();
        $roles = 'ROLE_USER';
        $use->setEmail($data['email']);
        $use->setPassword($data['password']);
        $use->setRoles($roles);
        $em->persist($use);
        $em->flush();
        $VarName = $userRepository->findBy(['email'=>$data['email']]);
        foreach ($VarName as $user) 
        {
            $userDTO[] = new UserDTO($user);
        }
        $data = $this->serializer->serialize($userDTO, 'json');
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apiuser/byid/{id}', methods: 'GET', requirements: ['id' => Requirement::DIGITS])]
    public function show(int $id , UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findById($id);

        foreach ($users as $user) 
        {
            $userDTO[] = new UserDTO($user);
        }
        $data = $this->serializer->serialize($userDTO, 'json');
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apiuser/byemailandpass', methods: 'POST')]
    public function showby(Request $req, UserRepository $userRepository): JsonResponse
    {
        // Récupérer les données de la requête JSON
        $data = json_decode($req->getContent(), true);
        
        // Vérifier que l'email et le mot de passe existent dans la requête
        if (!isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
        }
        
        // Rechercher un utilisateur par email
        $user = $userRepository->findOneBy(['email' => $data['email']]);
        
        // Vérifier si l'utilisateur existe
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        if ($data['password'] !== $user->getPassword()) {
            return new JsonResponse(['error' => 'Invalid password'], Response::HTTP_UNAUTHORIZED);
        }
        
        // Si l'utilisateur est trouvé et le mot de passe est correct, retourner les données de l'utilisateur
        $userDTO = new UserDTO($user);
        $data = $this->serializer->serialize($userDTO, 'json');
        
        return new JsonResponse($data['id'], Response::HTTP_OK, [], true);
    }
    

    #[Route('/apiuser/{id}/edit', methods: 'PUT' ,requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $req, int $id, #[MapRequestPayload] User $user, EntityManagerInterface $em, UserRepository $repository){
        $exist = $repository->find($id);

        $exist->setEmail($user->getEmail());
        $exist->setPassword($user->getPassword());
        $exist->setRoles($user->getRoles());

        // Update entity with form data
        $em->flush();

        return $this->json("OK update");
    }

    #[Route('/apiuser/delete/{id}', methods: 'DELETE' , requirements: ['id' => Requirement::DIGITS])]
    public function delete(User $use, EntityManagerInterface $entityManager)
    {
            $entityManager->remove($use);
            $entityManager->flush();

        return $this->json("OK deleted");
    }

}
