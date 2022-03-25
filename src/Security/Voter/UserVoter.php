<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, $user): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE]) && ($user instanceof User);
    }

    protected function voteOnAttribute(string $attribute, $user, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        if (!$currentUser instanceof User) {
            return false;
        }
        return $user === $currentUser;
    }
}