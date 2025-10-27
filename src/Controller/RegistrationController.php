<?php
namespace App\Controller;

use App\Entity\User;
use Attributes\DefaultEntity;
use Core\Attributes\Route;
use Core\Controller\Controller;
use Core\Http\Response;

#[DefaultEntity(entityName: User::class)]
class RegistrationController extends Controller
{
    #[Route(uri: '/register', routeName: 'registration')]
    public function register(): Response
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return $this->json([
        'status' => 'error',
        'message' => 'Méthode non autorisée'
        ], 405);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            return $this->json([
            'status' => 'error',
            'message' => 'Email ou mot de passe manquant'
            ], 400);
        }

        $existingUser= $this->getRepository()->findBy(['email' => $email]);
        if($existingUser){
            return $this->json([
                'status' => 'error',
                'message'=> "Cette email n'existe pas"
            ], 400);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']); // rôle par défaut

        $this->getRepository()->save($user);

        return $this->json([
        'status' => 'success',
        'message' => 'Utilisateur créé',
        'user' => ['email' => $email]
        ], 201);
    }
}
