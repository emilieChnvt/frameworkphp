<?php
namespace App\Authentication;

use Core\Authentication\Auth;
use Core\Authentication\TokenAuth;

class AuthManager
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    // Générer un token
    public function generateToken(array $data, int $expiry = 3600): string
    {
        $auth = new TokenAuth($this->secret);
        return $auth->generate($data, $expiry);
    }

    // Vérifier le token
    // AuthManager.php
    public function checkToken(string $token): ?array
    {
        $auth = new TokenAuth($this->secret);
        if ($auth->verify($token)) {
            return $auth->getPayload($token);
        }
        return null;
    }

}
