<?php

namespace Core\Attributes;

use Attribute;

#[Attribute]
class Column
{


    public function __construct(
        private string $name,
        private string|null $columnType = null,
        private ?int $length,
        private bool $nullable = false,

    ){}


}