<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;

final class ApicommandecontrollerController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/apicommande/all',methods: "GET")]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $data = $this->serializer->serialize($users, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apicommande/new', methods: 'POST')]
    public function create(#[MapRequestPayload] Commande $project, EntityManagerInterface $em){
        $em->persist($project);
        $em->flush();
        return $this->json("OK");
    }
    #[Route('/apicommande/byid/{id}', methods: 'GET', requirements: ['id' => Requirement::DIGITS])]
    public function show(User $user): JsonResponse
    {
        $data = $this->serializer->serialize($user, 'json');

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/apicommande/{id}/edit', methods: 'PUT' ,requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $responseData = $this->serializer->serialize($user, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK);
        }
        return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/apicommande/delete/{id}', methods: 'DELETE' , requirements: ['id' => Requirement::DIGITS])]
    public function delete(User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
