<?php

namespace Attributes;

use Attribute;

#[Attribute]
class TargetRepository
{
    private string $repoName;

    public function __construct(string $repoName)
    {
        $this->repoName = $repoName;
    }

}