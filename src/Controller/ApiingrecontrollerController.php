<?php

namespace App\Controller;

use App\DTO\IngredientDTO;
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
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

final class ApiingrecontrollerController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/apiingredient/all',methods: "GET")]
    public function index(IngredientRepository $ingredientRepository): JsonResponse
    {
    $ingres = $ingredientRepository->findAll();
    // Convert each Ingredient entity to a IngredientDTO
    $IngreDTOs = [];
    foreach ($ingres as $ingre) 
    {
        $IngreDTOs[] = new IngredientDTO($ingre);
    }
        $data = $this->serializer->serialize($IngreDTOs,'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apiingredient/new', methods: 'POST')]
    public function create(#[MapRequestPayload] Ingredient $project, EntityManagerInterface $em)
    {
        $em->persist($project);
        $em->flush();
        return $this->json("OK");
    }

    #[Route('/apiingredient/byid/{id}', methods: 'GET', requirements: ['id' => Requirement::DIGITS])]
    public function show(int $id , IngredientRepository $ingredientRepository): JsonResponse
    {
        $recette = $ingredientRepository->findById($id);
        $data = $this->serializer->serialize($recette, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apiingredient/{id}/edit', methods: 'PUT' ,requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $req, int $id, #[MapRequestPayload] Ingredient $recette, EntityManagerInterface $em, IngredientRepository $repository){
        $exist = $repository->find($id);
        $exist->setNom($recette->getNom());
        $exist->setStock($recette->getStock());
        $exist->setLinkimage($recette->getImagelink());

        // Update entity with form data
        $em->flush();
        return $this->json("OK update");
    }

    #[Route('/apiingredient/delete/{id}', methods: 'DELETE' , requirements: ['id' => Requirement::DIGITS])]
    public function delete(Ingredient $use, EntityManagerInterface $entityManager)
    {
            $entityManager->remove($use);
            $entityManager->flush();

        return $this->json("OK deleted");
    }
}