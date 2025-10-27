<?php

namespace App\Entity;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Attributes\TargetRepository;
use Core\Attributes\Column;
use Core\Attributes\Table;
use Core\Security\UserInterface;
use Core\Security\UserManagement;
use JsonSerializable;


#[Table(name: 'user')]
#[TargetRepository(repoName:UserRepository::class)]
class User extends UserManagement implements  JsonSerializable
{
    #[Column(name: 'id')]
    protected int $id;
    #[Column(name:'email', length: 255)]
    protected string $email;
    #[Column(name:'password', length: 255)]
    protected string $password;

    #[Column(name:'Roles', length: 255)]

    protected array $roles = ['ROLE_USER'];

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'roles' => $this->getRoles(),
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword( $clearPassword): void
    {
        $this->password = password_hash($clearPassword, PASSWORD_BCRYPT);
    }

    public function getAuthenticator(): string
    {
        return $this->email;
    }
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

}
