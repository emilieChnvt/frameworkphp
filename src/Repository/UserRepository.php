<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Attributes\TargetEntity;
use Core\Attributes\Column;
use Core\Repository\Repository;




#[TargetEntity(entityName: User::class)]
class UserRepository extends Repository
{








}