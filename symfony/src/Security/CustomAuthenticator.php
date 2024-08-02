<?php

namespace App\Security;

use App\Entity\Account;
use App\Entity\AccountSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class CustomAuthenticator extends AbstractLoginFormAuthenticator
{
  public function __construct(private UrlGeneratorInterface $urlGenerator, private EntityManagerInterface $entityManager) {}

  public function authenticate(Request $request): Passport
  {
    $email = $request->request->get('_username', '');
    $password = $request->request->get('_password', '');
    $csrfToken = $request->request->get('_csrf_token');

    $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

    return new Passport(
      new UserBadge($email, function ($userIdentifier) {
        /** @var Account $account */
        $account = $this->entityManager->getRepository(Account::class)->findOneBy([
          'email' => $userIdentifier,
          'is_deleted' => false
        ]);

        if (!$account) {
          throw new CustomUserMessageAuthenticationException('Invalid email or password');
        }

        if ($account->hasSession() && !$account->isAdmin()) {
          throw new CustomUserMessageAuthenticationException('Account is already logged in');
        }

        return $account;
      }),
      new PasswordCredentials($password),
      [
        new CsrfTokenBadge('authenticate', $csrfToken),
      ]
    );
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
  {
    /** @var Account */
    $account = $token->getUser();

    $session = new AccountSession();
    $session->setAccount($account);

    $this->entityManager->persist($session);
    $account->setCurrentSession($session);
    $this->entityManager->flush();

    if ($account->isAdmin()) {
      return new RedirectResponse($this->urlGenerator->generate('admin_joborders'));
    } else {
      return new RedirectResponse($this->urlGenerator->generate('user_dashboard'));
    }
  }


  public function getLoginUrl(Request $request): string
  {

    return $this->urlGenerator->generate('app_login');
  }
}
