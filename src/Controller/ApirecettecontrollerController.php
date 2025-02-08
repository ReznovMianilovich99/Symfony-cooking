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

    #[Route('/apirecette/all',methods: "GET")]
    public function index(RecetteRepository $recetteRepository): JsonResponse
    {
        $rectte = $recetteRepository->findAll();
        $data = $this->serializer->serialize($rectte, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apirecette/new', methods: 'POST')]
    public function create(#[MapRequestPayload] Recette $project, EntityManagerInterface $em){
        $em->persist($project);
        $em->flush();
        return $this->json("OK");
    }

    #[Route('/apirecette/byid/{id}', methods: 'GET', requirements: ['id' => Requirement::DIGITS])]
    public function show(int $id , RecetteRepository $recetteRepository): JsonResponse
    {
        $recette = $recetteRepository->findById($id);
        $data = $this->serializer->serialize($recette, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apirecette/{id}/edit', methods: 'PUT' ,requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $req, int $id, #[MapRequestPayload] Recette $Recette, EntityManagerInterface $em, RecetteRepository $repository){
        $exist = $repository->find($id);

        $exist->setEmail($Recette->getEmail());
        $exist->setPassword($Recette->getPassword());
        $exist->setRoles($Recette->getRoles());

        // Update entity with form data
        $em->flush();

        return $this->json("OK update");
    }

    #[Route('/apirecette/delete/{id}', methods: 'DELETE' , requirements: ['id' => Requirement::DIGITS])]
    public function delete(EntityManagerInterface $entityManager , RecetteRepository $repository)
    {
        $Recette = $RecetteRepository->findById($id);
        $entityManager->remove($Recette);
        $entityManager->flush();

        return $this->json("OK deleted");
    }
}