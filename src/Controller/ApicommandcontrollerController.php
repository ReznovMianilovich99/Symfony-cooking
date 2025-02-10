<?php

namespace App\Controller;

use App\DTO\CommandeDTO;
use App\Entity\Commande;
use App\Entity\Ingredient;
use App\Entity\Plat;
use App\Entity\User;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Requirement\Requirement;

final class ApicommandcontrollerController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/apiCommande/all',methods: "GET")]
    public function index(CommandeRepository $commandeRepository): JsonResponse
    {
        $rectte = $commandeRepository->findAll();
        $projectDTOs = array_map(fn(Commande $commande) => new CommandeDTO($commande), $rectte);
        $data = $this->serializer->serialize($projectDTOs,'json');
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apiCommande/new', methods: 'POST')]
public function create(Request $request, EntityManagerInterface $em ): Response
{
    $data = json_decode($request->getContent(), true);

    // Charger le user
    $user = $em->getRepository(User::class)->find($data['iduser']['id']);
    if (!$user) 
    {
        throw new \Exception("User non trouvé");
    }

    // Charger les Plats
    $plats = [];
    foreach ($data['idplats'] as $platData) 
    {
        $plat = $em->getRepository(Plat::class)->find($platData['id']);
        if (!$plat) 
        {
            throw new \Exception("Ingrédient non trouvé");
        }
        $plats[] = $plat;
    }
    // Créer la Commande
    $commande = new Commande();
    $commande->setIduser($user);
    $commande->setDateheurecommande(new \DateTimeImmutable($data['dateheurecommande']));
    $commande->setTotaleprice($data['totaleprice']);
    $commande->setPaiementcheck($data['paiementcheck']);
    $commande->setIsready($data['isready']);
    $commande->setIssend($data['issend']);
    foreach ($plats as $plat) 
    {
        $commande->addListplat($plat);
    }
    // Enregistrer la Commande
    $em->persist($commande);
    $em->flush();
    return $this->json("OK created");
}

    #[Route('/apiCommande/byid/{id}', methods: 'GET', requirements: ['id' => Requirement::DIGITS])]
    public function show(int $id , CommandeRepository $commandeRepository): JsonResponse
    {
        $commande = $commandeRepository->findById($id);
        $projectDTOs = array_map(fn(Commande $commande) => new CommandeDTO($commande), $commande);
        $data = $this->serializer->serialize($projectDTOs,'json');
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/apiCommande/{id}/edit', methods: 'PUT' ,requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $req, int $id, EntityManagerInterface $em, CommandeRepository $repository){
    $data = json_decode($req->getContent(), true);

        // Charger le user
    $user = $em->getRepository(User::class)->find($data['iduser']['id']);
    if (!$user) 
    {
        throw new \Exception("Plat non trouvé");
    }

    $exist = $repository->find($id);
    
    // Charger les Plats
    $plats = [];
    foreach ($data['idplats'] as $platData) 
    {
        $plat = $em->getRepository(Plat::class)->find($platData['id']);
        if (!$plat) 
        {
            throw new \Exception("Ingrédient non trouvé");
        }
        $plats[] = $plat;
    }

    // Créer la Commande
    $exist->setIduser($user);
    $exist->setDateheurecommande($data['dateheurecommande']);
    $exist->setTotaleprice($data['totaleprice']);
    $exist->setPaiementcheck($data['paiementcheck']);
    $exist->setIsready($data['isready']);
    $exist->setIssend($data['issend']);

    $exist->removeAllListplat();

    foreach ($plats as $plat) 
    {
        $exist->addListplat($plat);
    }

        // Update entity with form data
        $em->persist($exist);
        $em->flush();
        return $this->json("OK update");
    }

    #[Route('/apiCommande/delete/{id}', methods: 'DELETE' , requirements: ['id' => Requirement::DIGITS])]
    public function delete(Commande $use, EntityManagerInterface $entityManager)
    {
            $entityManager->remove($use);
            $entityManager->flush();

        return $this->json("OK deleted");
    }
}
