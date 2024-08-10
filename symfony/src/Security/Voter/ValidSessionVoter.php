<?php

namespace App\Security\Voter;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Validator\Constraints\IsNull;

class ValidSessionVoter extends Voter
{
  public const HAS_VALID_SESSION = 'HAS_VALID_SESSION';

  public function __construct(
    private Security $security,
    private EntityManagerInterface $entityManager,
    private LoggerInterface $logger
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



    $session = $user->getCurrentSession();
    if (is_null($session) || !is_null($session->getLogoutAt())) {
      $this->security->logout(false);
      return false;
    }

    return true;
  }
}
