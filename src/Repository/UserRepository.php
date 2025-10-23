<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Attributes\TargetEntity;
use Core\Repository\Repository;




#[TargetEntity(entityName: User::class)]
class UserRepository extends Repository
{



    public function save(User $user): int
    {
        $this->pdo->prepare("INSERT INTO $this->tableName (email, password, roles) VALUES (:email, :password, :roles)")
            ->execute([
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'roles' => $user->getRoles()

            ]);

        return $this->pdo->lastInsertId();
    }

    public function update(User $user): int

    {
        $this->pdo->prepare("UPDATE $this->tableName SET email = :email,  password = :password, roles = :roles WHERE id = :id")

            ->execute([
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'roles' => $user->getRoles(),
                'id' => $user->getId()
            ]);
        return $this->find($user->getId());
    }




}