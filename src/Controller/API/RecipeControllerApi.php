<?php

declare(strict_types=1);

namespace App\Controller\API;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\RecipeRepository;
use Symfony\Component\Routing\Requirement\Requirement;
use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\RecipeDTO;
use App\Mapper\RecipeMapper;

#[Route('/api')]
final class RecipeControllerApi extends AbstractController
{
    #[Route('/recipes', name: 'api_recipes', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository, Request $request): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $recipes = $recipeRepository->listPaginatedRecipes($page);

        return $this->json(
            ['recipes' => $recipes], 
            200, 
            [], 
            ['groups' => ['recipes.index']]
        );
    }

    #[Route('/recipes/{id}', name: 'api_recipes_show', requirements: ['id' => Requirement::DIGITS])]
    public function show(Recipe $recipe): JsonResponse
    {
        return $this->json(
            $recipe, 
            200, 
            [], 
            ['groups' => ['recipes.index', 'recipes.show']]
        );
    }

    #[Route("/recipes", methods: ["POST"])]
    public function create(
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['recipes.create']
            ]
        )]
        Recipe $recipe,
        EntityManagerInterface $em
    )
    {
        dd($recipe);
        $em->persist($recipe);
        $em->flush();
        return $this->json($recipe, 200, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }

    #[Route("/recipesDTO", methods: ["POST"])]
    public function createWithDTO(
        #[MapRequestPayload]
        RecipeDTO $recipeDTO,
        EntityManagerInterface $em,
        RecipeMapper $recipeMapper
    )
    {
        $recipe = $recipeMapper->fromDTO($recipeDTO);
        dd($recipe);
        $em->persist($recipe);
        $em->flush();
        return $this->json($recipe, 200, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }


}
