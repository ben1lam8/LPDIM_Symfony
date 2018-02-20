<?php


namespace AppBundle\Security\Authorization;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const SUPPORTED_ATTRIBUTES = ["ROLE_ADMIN"];

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, self::SUPPORTED_ATTRIBUTES);
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /**
         * @var User
         */
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }
        
        /**
         * @var string
         */
        $requiredRole = $attribute;

        return in_array($requiredRole, self::SUPPORTED_ATTRIBUTES) && in_array($requiredRole, $user->getRoles());
    }
}
