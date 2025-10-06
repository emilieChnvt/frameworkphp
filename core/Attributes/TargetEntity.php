<?php

namespace Attributes;

use Attribute;

#[Attribute]
class TargetEntity
{
    private string $entityName;

    public function __construct(string $entityName)
    {
        $this->entityName = $entityName;
    }

}