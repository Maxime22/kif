<?php

declare(strict_types=1);

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use App\Entity\Recipe;
use App\Entity\User;

class RecipeOwnerVoter extends Voter
{
    const EDIT = 'edit_recipe';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        if (!$subject instanceof Recipe) {
            return false;
        }

        return true;

    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            $vote?->addReason('The user is not logged in.');
            return false;
        }

        // you know $subject is a Recipe object, thanks to `supports()`
        /** @var Recipe $recipe */
        $recipe = $subject;

        return match($attribute) {
            self::EDIT => $this->canEdit($recipe, $user, $vote),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canEdit(Recipe $recipe, User $user, ?Vote $vote): bool
    {
        if ($user === $recipe->getAuthor()) {
            return true;
        }

        $vote?->addReason(sprintf(
            'The logged in user (username: %s) is not the author of this recipe (id: %d).',
            $user->getUsername(), $recipe->getId()
        ));

        return false;
    }
}