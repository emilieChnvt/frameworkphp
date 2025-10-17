<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Attributes\TargetEntity;
use Core\Repository\Repository;




#[TargetEntity(entityName: Post::class)]
class UserRepository extends Repository
{

    public function save(User $user): int
    {
        $this->pdo->prepare("INSERT INTO $this->tableName (email, password) VALUES (:email, :password)")
            ->execute([
                'email' => $user->getEmail(),
                'password' => $user->getPassword()
            ]);

        return $this->pdo->lastInsertId();
    }

    public function update(User $user): int

    {
        $this->pdo->prepare("UPDATE $this->tableName SET email = :email,  password = :password WHERE id = :id")

            ->execute([
                'title' => $user->getEmail(),
                'password' => $user->getPassword(),
                'id' => $user->getId()
            ]);
        return $this->find($user->getId());
    }




}