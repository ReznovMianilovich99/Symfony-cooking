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

#[Route('/api/plat')]
final class ApiplatcontrollerController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/all',name: 'app_plat_index', methods: ['GET'])]
    public function index(PlatRepository $platRepository): JsonResponse
    {
        $plats = $platRepository->findAll();
        $data = $this->serializer->serialize($plats, 'json', ['groups' => 'plat:read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/byid/{id}', name: 'app_plat_show', methods: ['GET'])]
    public function show(Plat $plat): JsonResponse
    {
        $data = $this->serializer->serialize($plat, 'json', ['groups' => 'plat:read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/new', name: 'app_plat_new', methods: ['POST'])]
    public function create(#[MapRequestPayload] Plat $project, EntityManagerInterface $em){
        $em->persist($project);
        $em->flush();
        return $this->json("OK");
    }

    #[Route('/{id}/edit', name: 'app_plat_edit', methods: ['PUT'])]
    public function edit(Request $request, Plat $plat, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(PlatType::class, $plat);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $responseData = $this->serializer->serialize($plat, 'json', ['groups' => 'plat:read']);
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/delete/{id}', name: 'app_plat_delete', methods: ['DELETE'])]
    public function delete(Plat $plat, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($plat);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}