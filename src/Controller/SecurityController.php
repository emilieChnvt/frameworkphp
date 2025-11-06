<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Attributes\DefaultEntity;
use Core\Attributes\Route;
use App\Authentication\AuthManager;
use App\Entity\User;
use Core\Controller\Controller;
use Core\Http\Response;
use EmilieChnvt\TokenBundle\JWT;

#[DefaultEntity(entityName: User::class)]
class SecurityController extends Controller
{
    private AuthManager $auth;

    public function __construct()
    {
        parent::__construct();
        $this->auth = new AuthManager('SECRETE');
    }

    #[Route(uri: '/login', routeName: 'login')]
    public function login(): Response
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->getRepository()->findOneBy(['email' => $email]);
            if ($user && $user->passwordMatches($password)) {
                $payload = [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'roles' => $user->getRoles(),
                ];

                // Passer le payload directement
                $token = $this->auth->generateToken($payload, 3600);

                return $this->json([
                    'message' => 'Authentification réussie',
                    'token' => $token,
                    'user' => $payload
                ]);
            }

            return $this->json([
                'status' => 'error',
                'message' => 'Email ou mot de passe incorrect',
            ], 401);
        }

        return $this->render('auth/index', []);
    }


    #[Route(uri: '/profile', routeName: 'profile')]
    public function profile(): Response
    {
        // Récup  token depuis l'en-tête Authorization
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['Authorization'] ?? '';
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $this->json(['error' => 'Token manquant'], 401); // Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6...
        }

        $token = $matches[1];
        $payload = $this->auth->checkToken($token);

        // Vérif que l'id existe
        if (!$payload || !isset($payload['id'])) {
            return $this->json(['error' => 'Token invalide ou expiré'], 401);
        }

        $user = $this->getRepository()->find((int)$payload['id']);
        if (!$user) {
            return $this->json(['error' => 'Utilisateur introuvable'], 404);
        }

        return $this->json([
            'message' => 'Token valide',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ]
        ]);
    }

    #[Route(uri: '/test', routeName: 'test')]
    public function index(): Response
    {
        $users = $this->getRepository()->findAll();
        return $this->json($users);
    }



}