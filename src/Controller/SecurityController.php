<?php

namespace App\Controller;


use Core\Attributes\Route;
use Core\Authentication\TokenAuth;
use Core\Authentication\Auth;
use Core\Controller\Controller;

class SecurityController extends Controller
{
    private string $secret = 'MON_SECRET_SUPER_SECRETE';

    // Génération du token
    #[Route(uri:'/login', routeName: 'login')]

    public function login()
    {
        $auth = new TokenAuth('MON_SECRET_SUPER_SECRETE');
        $token = $auth->generate(['user_id' => 123], 3600);


        echo json_encode(['token' => $token]);
    }

    #[Route(uri:'/profile', routeName: 'profile')]

    public function profile()
    {
        $middleware = new Auth($this->secret);
        $payload = $middleware->handle(); // Vérifie le token

        echo json_encode([
            'message' => 'Accès autorisé',
            'user' => $payload
        ]);
    }
}
