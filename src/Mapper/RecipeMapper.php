<?php

namespace App\Mapper;

use App\DTO\RecipeDTO;
use App\Entity\Recipe;

class RecipeMapper
{
    public function fromDTO(RecipeDTO $dto): Recipe
    {
        $recipe = new Recipe();
        $recipe->setName($dto->name);
        return $recipe;
    }
}