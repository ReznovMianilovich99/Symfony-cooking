<?php

namespace App\Controller;

use App\Entity\Historique;
use App\Form\HistoriqueType;
use App\Repository\HistoriqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/apicontro/historique')]
final class ApihistocontrollerController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/all',name: 'app_historique_index', methods: ['GET'])]
    public function index(HistoriqueRepository $historiqueRepository): JsonResponse
    {
        $historiques = $historiqueRepository->findAll();
        $data = $this->serializer->serialize($historiques, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/byid/{id}', name: 'app_historique_show', methods: ['GET'])]
    public function show(Historique $historique): JsonResponse
    {
        $data = $this->serializer->serialize($historique, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/new', name: 'app_historique_new', methods: ['POST'])]
    public function create(#[MapRequestPayload] Historique $project, EntityManagerInterface $em){
        $em->persist($project);
        $em->flush();
        return $this->json("OK");
    }

    #[Route('/{id}/edit', name: 'app_historique_edit', methods: ['PUT'])]
    public function edit(Request $request, Historique $historique, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(HistoriqueType::class, $historique);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $responseData = $this->serializer->serialize($historique, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/delete/{id}', name: 'app_historique_delete', methods: ['DELETE'])]
    public function delete(Historique $historique, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($historique);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}