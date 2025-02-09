<?php

namespace App\Controller;

use App\DTO\RecetteDTO;
use App\Entity\Recette;
use App\Entity\Ingredient;
use App\Entity\Plat;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Requirement\Requirement;


final class ApirecettecontrollerController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/apirecette/all',methods: "GET")]
    public function index(RecetteRepository $recetteRepository): JsonResponse
    {
        $rectte = $recetteRepository->findAll();
        $projectDTOs = array_map(fn(Recette $recette) => new RecetteDTO($recette), $rectte);
        $data = $this->serializer->serialize($projectDTOs,'json');
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apirecette/new', methods: 'POST')]
public function create(Request $request, EntityManagerInterface $em ): Response
{
    $data = json_decode($request->getContent(), true);

    // Charger le Plat
    $plat = $em->getRepository(Plat::class)->find($data['idplat']['id']);
    if (!$plat) {
        throw new \Exception("Plat non trouvé");
    }

    // Charger les Ingrédients
    $ingredients = [];
    foreach ($data['idingredient'] as $ingredientData) 
    {
        $ingredient = $em->getRepository(Ingredient::class)->find($ingredientData['id']);
        if (!$ingredient) 
        {
            throw new \Exception("Ingrédient non trouvé");
        }
        $ingredients[] = $ingredient;
    }

    // Créer la Recette
    $recette = new Recette();
    $recette->setIdplat($plat);
    foreach ($ingredients as $ingredient) 
    {
        $recette->addIdingredient($ingredient);
    }
    // Enregistrer la Recette
    $em->persist($recette);
    $em->flush();

    return $this->json("OK created");
}

    #[Route('/apirecette/byid/{id}', methods: 'GET', requirements: ['id' => Requirement::DIGITS])]
    public function show(int $id , RecetteRepository $recetteRepository): JsonResponse
    {
        $recette = $recetteRepository->findById($id);
        $projectDTOs = array_map(fn(Recette $recettes) => new RecetteDTO($recettes), $recette);
        $data = $this->serializer->serialize($projectDTOs, 'json');
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apirecette/{id}/edit', methods: 'PUT' ,requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $req, int $id, EntityManagerInterface $em, RecetteRepository $repository){
        $data = json_decode($req->getContent(), true);

        // Charger le Plat
        $plat = $em->getRepository(Plat::class)->find($data['idplat']['id']);
        if (!$plat) {
            throw new \Exception("Plat non trouvé");
        }
    
        // Charger les Ingrédients
        $ingredients = [];
        foreach ($data['idingredient'] as $ingredientData) 
        {
            $ingredient = $em->getRepository(Ingredient::class)->find($ingredientData['id']);
            if (!$ingredient) 
            {
                throw new \Exception("Ingrédient non trouvé");
            }
            $ingredients[] = $ingredient;
        }

        $exist = $repository->find($id);

            // Créer la Recette
    $exist->setIdplat($plat);
    foreach ($ingredients as $ingredient) 
    {
        $exist->addIdingredient($ingredient);
    }

        // Update entity with form data
        $em->flush();
        return $this->json("OK update");
    }

    #[Route('/apirecette/delete/{id}', methods: 'DELETE' , requirements: ['id' => Requirement::DIGITS])]
    public function delete(Recette $use, EntityManagerInterface $entityManager)
    {
            $entityManager->remove($use);
            $entityManager->flush();

        return $this->json("OK deleted");
    }
}