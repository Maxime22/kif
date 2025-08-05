<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\RecipeCategory;
use App\Form\RecipeCategoryType;
use App\Repository\RecipeCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

final class RecipeCategoryController extends AbstractController
{
    #[Route('/recipe/category', name: 'app_recipe_category')]
    public function index(RecipeCategoryRepository $recipeCategoryRepository): Response
    {
        $categories = $recipeCategoryRepository->findAll();
        return $this->render('recipe_category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/recipe/category/new', name: 'app_recipe_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new RecipeCategory();
        $form = $this->createForm(RecipeCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Générer le slug uniquement après validation réussie
            $slugger = new AsciiSlugger();
            $slug = strtolower($slugger->slug($category->getName())->toString());
            $category->setSlug($slug);
            
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_category');
        }

        return $this->render('recipe_category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recipe/category/{id}/edit', name: 'app_recipe_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RecipeCategory $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecipeCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_category');
        }

        return $this->render('recipe_category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recipe/category/{id}/delete', name: 'app_recipe_category_delete', methods: ['DELETE'])]
    public function delete(RecipeCategory $category, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('app_recipe_category');
    }
}
