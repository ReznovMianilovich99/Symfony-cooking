<?php
    namespace App\Controller;
    use App\Service\FirebaseService;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    class FirebaseAuthController extends AbstractController 
    {
        private $firebaseService;
        public function __construct(FirebaseService $firebaseService) { $this->firebaseService = $firebaseService; }
        #[Route('/firebase/verify-token', name: 'firebase_verify_token')]
        public function verifyToken(Request $request): Response {
            $idToken = $request->headers->get('Authorization');
            if (strpos($idToken, 'Bearer ') === 0) { $idToken = substr($idToken, 7); 
            }
            try 
            {
                $verifiedToken = $this->firebaseService->verifyIdToken($idToken);
                return new Response('Utilisateur authentifié avec UID: ' . $verifiedToken->getClaim('sub'));
            } catch (\Exception $e) { return new Response('Erreur d\'authentification : ' . $e->getMessage(), 401); 
            }
        }
    }
?>