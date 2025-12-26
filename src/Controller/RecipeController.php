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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Security\Voter\RecipeOwnerVoter;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class RecipeController extends AbstractController
{
    #[Route('/recipes', name: 'app_recipe')]
    public function index(RecipeRepository $recipeRepository): Response
    {
       $recipes = $recipeRepository->findAll();
       return $this->render('recipe/index.html.twig', [
           'recipes' => $recipes,
       ]);
    }

    #[Route('/recipe/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $recipe->setAuthor($this->getUser());
            $entityManager->persist($recipe);
            $entityManager->flush();
            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted(attribute: RecipeOwnerVoter::EDIT, subject: 'recipe')]
    #[Route('/recipe/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
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

    #[Route('/recipe/{id}/delete', name: 'app_recipe_delete', methods: ['DELETE'])]
    public function delete(Recipe $recipe, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifier le jeton CSRF pour les requêtes POST/DELETE
        if ($request->isMethod('DELETE')) {
            $submittedToken = $request->request->get('_token');
            if (!$this->isCsrfTokenValid('delete'.$recipe->getId(), $submittedToken)) {
                throw $this->createAccessDeniedException('Invalid CSRF token');
            }
        }
        
        $entityManager->remove($recipe);
        $entityManager->flush();
        
        $this->addFlash('success', 'Recipe deleted successfully');
        return $this->redirectToRoute('app_recipe');
    }
}
