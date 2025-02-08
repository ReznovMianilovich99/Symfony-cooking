<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/apicontro/ingredient')]
final class ApiingrecontrollerController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/all',name: 'app_ingredient_index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository): JsonResponse
    {
        $ingredients = $ingredientRepository->findAll();
        $data = $this->serializer->serialize($ingredients, 'json', ['groups' => 'ingredient:read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/byid/{id}', name: 'app_ingredient_show', methods: ['GET'])]
    public function show(Ingredient $ingredient): JsonResponse
    {
        $data = $this->serializer->serialize($ingredient, 'json', ['groups' => 'ingredient:read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/new', name: 'app_ingredient_new', methods: ['POST'])]
    public function create(#[MapRequestPayload] Ingredient $project, EntityManagerInterface $em){
        $em->persist($project);
        $em->flush();
        return $this->json("OK");
    }

    #[Route('/{id}/edit', name: 'app_ingredient_edit', methods: ['PUT'])]
    public function edit(Request $request, Ingredient $ingredient, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $responseData = $this->serializer->serialize($ingredient, 'json', ['groups' => 'ingredient:read']);
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/delete/{id}', name: 'app_ingredient_delete', methods: ['DELETE'])]
    public function delete(Ingredient $ingredient, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($ingredient);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}