<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, RecipeCategory>
     */
    #[ORM\ManyToMany(targetEntity: RecipeCategory::class, mappedBy: 'recipes')]
    private Collection $recipeCategories;

    public function __construct()
    {
        $this->recipeCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, RecipeCategory>
     */
    public function getRecipeCategories(): Collection
    {
        return $this->recipeCategories;
    }

    public function addRecipeCategory(RecipeCategory $recipeCategory): static
    {
        if (!$this->recipeCategories->contains($recipeCategory)) {
            $this->recipeCategories->add($recipeCategory);
            $recipeCategory->addRecipe($this);
        }

        return $this;
    }

    public function removeRecipeCategory(RecipeCategory $recipeCategory): static
    {
        if ($this->recipeCategories->removeElement($recipeCategory)) {
            $recipeCategory->removeRecipe($this);
        }

        return $this;
    }
}
