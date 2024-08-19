<?php

namespace App\Security\Voter;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ValidSessionVoter extends Voter
{
  public const HAS_VALID_SESSION = 'HAS_VALID_SESSION';

  public function __construct(
    private Security $security,
    private EntityManagerInterface $entityManager,
  ) {}

  public function supports(string $attribute, mixed $subject): bool
  {
    return str_starts_with($attribute, 'ROLE_');
  }

  public function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
  {
    $user = $token->getUser();

    if (!$user instanceof Account) {
      return false;
    }

    if (!$user->hasSession()) {
      $this->security->logout(false);
      return false;
    }

    return true;
  }
}
