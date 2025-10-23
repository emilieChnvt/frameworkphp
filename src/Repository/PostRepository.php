<?php

namespace App\Repository;

use App\Entity\Post;
use Attributes\TargetEntity;
use Core\Repository\Repository;


#[TargetEntity(entityName: Post::class)]
class PostRepository extends Repository
{

    public function save(Post $post): int
    {
        $this->pdo->prepare("INSERT INTO $this->tableName (title, content) VALUES (:title, :content)")
            ->execute([
                'title' => $post->getTitle(),
                'content' => $post->getContent()
            ]);

        return $this->pdo->lastInsertId();
    }

        public function update(Post $post)

        {
            $this->pdo->prepare("UPDATE $this->tableName SET title = :title,  content = :content WHERE id = :id")

            ->execute([
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'id' => $post->getId()
            ]);
                return $this->find($post->getId());
                }




}