<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiplatcontrollerController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/apiplat/all',methods: "GET")]
    public function index(PlatRepository $platRepository): JsonResponse
    {
        $rectte = $platRepository->findAll();
        $projectDTOs = array_map(fn(Recette $recette) => new RecetteDTO($recette), $rectte);
        $data = $this->serializer->serialize($projectDTOs,'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apiplat/new', methods: 'POST')]
    public function create(#[MapRequestPayload] Recette $project, EntityManagerInterface $em)
    {
        $em->persist($project);
        $em->flush();
        return $this->json("OK");
    }

    #[Route('/apiplat/byid/{id}', methods: 'GET', requirements: ['id' => Requirement::DIGITS])]
    public function show(int $id , PlatRepository $platRepository): JsonResponse
    {
        $recette = $platRepository->findById($id);
        $projectDTOs = array_map(fn(Recette $recettes) => new RecetteDTO($recettes), $recette);
        $data = $this->serializer->serialize($recette, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apiplat/{id}/edit', methods: 'PUT' ,requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $req, int $id, #[MapRequestPayload] Recette $recette, EntityManagerInterface $em, PlatRepository $repository){
        $exist = $repository->find($id);
        $exist->setIdplat($recette->getIdplat());
        // Update entity with form data
        $em->flush();

        return $this->json("OK update");
    }

    #[Route('/apiplat/delete/{id}', methods: 'DELETE' , requirements: ['id' => Requirement::DIGITS])]
    public function delete(EntityManagerInterface $entityManager , PlatRepository $repository)
    {
        $Recette = $repository->findById($id);
        $entityManager->remove($Recette);
        $entityManager->flush();

        return $this->json("OK deleted");
    }
}