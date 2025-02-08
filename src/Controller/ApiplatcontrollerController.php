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
use Symfony\Component\Routing\Requirement\Requirement;

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
        $data = $this->serializer->serialize($rectte,'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apiplat/new', methods: 'POST')]
    public function create(#[MapRequestPayload] Plat $project, EntityManagerInterface $em)
    {
        $em->persist($project);
        $em->flush();
        return $this->json("OK");
    }

    #[Route('/apiplat/byid/{id}', methods: 'GET', requirements: ['id' => Requirement::DIGITS])]
    public function show(int $id , PlatRepository $platRepository): JsonResponse
    {
        $recette = $platRepository->findById($id);
        $data = $this->serializer->serialize($recette, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apiplat/{id}/edit', methods: 'PUT' ,requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $req, int $id, #[MapRequestPayload] Plat $recette, EntityManagerInterface $em, PlatRepository $repository){
        $exist = $repository->find($id);
        $exist->setNom($recette->getNom());
        $exist->setPrix($recette->getPrix());
        $exist->setCookingtime($recette->getCookingtime());
        $exist->setLinkimage($recette->getLinkimage());

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