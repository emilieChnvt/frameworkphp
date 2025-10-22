<?php

namespace App\Controller;

use Core\Attributes\Route;
use App\Authentication\AuthManager;
use Core\Controller\Controller;

class SecurityController extends Controller
{
    private AuthManager $auth;

    public function __construct()
    {
        $this->auth = new AuthManager('MON_SECRET_SUPER_SECRETE');
    }

    #[Route(uri:'/login', routeName: 'login')]
    public function login()
    {
        $token = $this->auth->generateToken(['user_id' => 123], 3600);
        echo json_encode(['token' => $token]);
    }

    #[Route(uri:'/profile', routeName: 'profile')]
    public function profile()
    {
        $payload = $this->auth->checkToken(); // Vérifie le token
        echo json_encode([
            'message' => 'Accès autorisé',
            'user' => $payload
        ]);
    }
}
