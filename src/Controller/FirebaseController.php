<?php
    namespace App\Controller;
    use App\Service\FirebaseService;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    class FirebaseController extends AbstractController {
        private $firebaseService;
        public function __construct(FirebaseService $firebaseService) 
        { $this->firebaseService = $firebaseService; }
        #[Route('/firebase/create-user', name: 'firebase_create_user')]
        public function createUser(): Response 
        {
            $email = 'user@example.com';
            $password = 'securepassword';
            try 
            {
                $uid = $this->firebaseService->createUser($email, $password);
                return new Response('Utilisateur créé avec UID: ' . $uid);
            } 
            catch (\Exception $e) { return new Response('Erreur: ' . $e->getMessage()); }
        }
        #[Route('/firebase/get-user-data/{uid}', name: 'firebase_get_user_data')]
        public function getUserData(string $uid): Response 
        {
            try 
            {
                $userData = $this->firebaseService->getUserData($uid);
                return new Response('Données utilisateur : ' . json_encode($userData));
            } catch (\Exception $e) 
            { return new Response('Erreur : ' . $e->getMessage()); }
        }
    }
?>