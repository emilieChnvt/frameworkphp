<?php
namespace Core\Authentication;

class Auth
{
    protected TokenAuth $auth;

    public function __construct(string $secret)
    {
        $this->auth = new TokenAuth($secret);
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
    }

    /**
     * Vérifie le token JWT présent dans les headers Authorization
     * @return array Le payload du token
     */
    public function handle(): array
    {
        // On interdit toute méthode autre que POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['message' => 'Méthode non autorisée']);
            exit;
        }

        // Récupération du token dans les headers
        $token = null;
        if (isset($_SERVER['Authorization'])) {
            $token = trim($_SERVER['Authorization']);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = trim($_SERVER['HTTP_AUTHORIZATION']);
        } elseif (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                $token = trim($headers['Authorization']);
            }
        }

        // Vérifie que la chaîne commence par "Bearer "
        if (!$token || !preg_match('/Bearer\s(\S+)/', $token, $matches)) {
            http_response_code(400);
            echo json_encode(['message' => 'Token introuvable']);
            exit;
        }

        $token = str_replace('Bearer ', '', $token);

        // Vérification du token
        if (!$this->auth->verify($token)) {
            http_response_code(403);
            echo json_encode(['message' => 'Token invalide ou expiré']);
            exit;
        }


        return $this->auth->getPayload($token);
    }
}
