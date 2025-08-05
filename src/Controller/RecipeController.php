<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecipeRepository;
use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RecipeType;
use Doctrine\ORM\EntityManagerInterface;

final class RecipeController extends AbstractController
{
    #[Route('/recettes', name: 'app_recipe')]
    public function index(RecipeRepository $recipeRepository): Response
    {
       $recipes = $recipeRepository->findAll();
       return $this->render('recipe/index.html.twig', [
           'recipes' => $recipes,
       ]);
    }

    #[Route('/recette/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($recipe);
            $entityManager->flush();
            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/recette/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('recipe/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/recette/{id}/delete', name: 'app_recipe_delete', methods: ['DELETE'])]
    public function delete(Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($recipe);
        $entityManager->flush();
        return $this->redirectToRoute('app_recipe');
    }
}
