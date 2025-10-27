<?php

namespace App\Repository;

use App\Entity\Post;
use Attributes\TargetEntity;
use Core\Attributes\Column;
use Core\Repository\Repository;


#[TargetEntity(entityName: Post::class)]
class PostRepository extends Repository
{




}