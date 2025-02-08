<?php
    namespace App\Service;
    use Kreait\Firebase\Factory;
    use Kreait\Firebase\Auth;
    class FirebaseService {
        private $auth;
        public function __construct(Factory $firebaseFactory) {
            $firebase = $firebaseFactory->withServiceAccount('%kernel.project_dir%/config/firebase/serviceAccountKey.json');
            $this->auth = $firebase->createAuth();
        }
        public function createUser(string $email, string $password): string {
            try {
                $user = $this->auth->createUserWithEmailAndPassword($email, $password);
                return $user->uid;
            } catch (\Exception $e) { throw new \RuntimeException('Erreur lors de la création de l\'utilisateur : ' . $e->getMessage()); }
        }
        public function verifyIdToken(string $idToken): \Kreait\Firebase\Auth\Token {
            try {
                $verifiedIdToken = $this->auth->verifyIdToken($idToken);
                return $verifiedIdToken;
            } catch (\Kreait\Firebase\Exception\Auth\InvalidIdToken $e) { throw new \Exception('Jeton invalide ou expiré: ' . $e->getMessage()); }
        }
        public function getUserData(string $uid) {
            $firestore = $this->firebase->createFirestore();
            $document = $firestore->collection('users')->document($uid);
            $snapshot = $document->snapshot();
            if ($snapshot->exists()) { return $snapshot->data(); }
            
            throw new \Exception("Utilisateur non trouvé");
        }
    }
?>