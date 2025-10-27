<?php

namespace App\Entity;


use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Attributes\TargetRepository;
use Core\Attributes\Column;
use Core\Attributes\Table;
use JsonSerializable;

#[Table(name: 'posts')]
#[TargetRepository(repoName:PostRepository::class)]
class Post implements JsonSerializable
{

    #[Column(name: 'id')]
    private ?int $id = null;
    #[Column(name: 'title', length: 255)]

    private string $title;
    #[Column(name: 'content', length: 255)]

    private string $content;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
        ];
    }


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