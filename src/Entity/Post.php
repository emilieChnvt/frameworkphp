<?php

namespace App\Entity;


use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Attributes\TargetRepository;
use Core\Attributes\Table;

#[Table(name: 'posts')]
#[TargetRepository(repoName:PostRepository::class)]
class Post
{

    private int $id;
    private string $title;
    private string $content;



    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getComments()
    {
        $commentRepository = new CommentRepository();
        return $commentRepository->getCommentsByPost($this);
    }

}