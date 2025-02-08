<?php

namespace App\Controller;

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
        $data = $this->serializer->serialize($users, 'json');

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
    public function create(#[MapRequestPayload] User $project, EntityManagerInterface $em)
    {
        $em->persist($project);
        $em->flush();
        return $this->json("OK");
    }

    #[Route('/apiuser/byid/{id}', methods: 'GET', requirements: ['id' => Requirement::DIGITS])]
    public function show(int $id , UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->findById($id);
        $data = $this->serializer->serialize($user, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
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
