<?php

namespace App\DTO;

class RecipeDTO
{
    public function __construct(
        public ?string $name = null,
    ) {
    }
}
