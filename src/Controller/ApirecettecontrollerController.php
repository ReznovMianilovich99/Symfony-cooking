<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/recette')]
final class ApirecettecontrollerController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/all',name: 'app_recette_index', methods: ['GET'])]
    public function index(RecetteRepository $recetteRepository): JsonResponse
    {
        $recettes = $recetteRepository->findAll();
        $data = $this->serializer->serialize($recettes, 'json', ['groups' => 'recette:read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/byid/{id}', name: 'app_recette_show', methods: ['GET'])]
    public function show(Recette $recette): JsonResponse
    {
        $data = $this->serializer->serialize($recette, 'json', ['groups' => 'recette:read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/new', name: 'app_recette_new', methods: ['POST'])]
    public function create(#[MapRequestPayload] Recette $project, EntityManagerInterface $em){
        $em->persist($project);
        $em->flush();
        return $this->json("OK");
    }

    #[Route('/{id}/edit', name: 'app_recette_edit', methods: ['PUT'])]
    public function edit(Request $request, Recette $recette, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(RecetteType::class, $recette);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $responseData = $this->serializer->serialize($recette, 'json', ['groups' => 'recette:read']);
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/delete/{id}', name: 'app_recette_delete', methods: ['DELETE'])]
    public function delete(Recette $recette, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($recette);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}