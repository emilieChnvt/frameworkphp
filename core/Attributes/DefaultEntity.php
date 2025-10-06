<?php

namespace Attributes;

use Attribute;

#[Attribute]
class DefaultEntity
{
    private string $entityName;

    public function __construct(string $entityName)
    {
        $this->entityName = $entityName;
    }

}