<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use App\Repository\PersonnelRepository;
use App\Service\Referer;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountsController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $entityManager,
    private PersonnelRepository $personnelRepository,
    private AccountRepository $accountRepository,
    private Referer $referer,
  ) {}

  #[Route('/admin/accounts', name: 'accounts_index', methods: ['GET', 'POST'])]
  public function index(UserPasswordHasherInterface $passwordHasher, PaginatorInterface $paginator, Request $request): Response
  {
    $qb = $this->accountRepository->createJoinedQueryBuilder();

    $account = new Account();
    $form = $this->createForm(AccountType::class, $account);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $passwordHash = $passwordHasher->hashPassword(
        $account,
        $account->getPassword(),
      );
      $account->setPassword($passwordHash);

      $this->entityManager->persist($account);

      $this->entityManager->flush();
      $this->addFlash('notifications', [
        'title' => 'Added account successfully',
        'message' => "Successfully added account '" . $account->getEmail() . "'"
      ]);

      return $this->referer->redirect(
        $this->redirectToRoute('accounts_index', [], 303)
      );
    }


    $search = $request->query->get('search');
    $page = $request->query->getInt('page', 1);
    $accounts = $paginator->paginate(
      $qb,
      $page,
      10
    );

    if ($request->query->has('table')) {
      return $this->render('accounts-table.twig', [
        'accounts' => $accounts,
      ]);
    }

    return $this->render('accounts.twig', [
      'addForm' => $form,
      'accounts' => $accounts,
      'page' => $page,
      'search' => $search,
    ]);
  }

  #[Route('/admin/accounts/{id}/logout', name: 'account_logout', methods: ['GET'])]
  public function logoutAccount(Account $account): Response
  {
    $account->removeSession();
    $this->entityManager->flush();

    return $this->redirectToRoute('accounts_index');
  }

  #[Route('/admin/accounts/{id}', name: 'account_delete', methods: ['DELETE'])]
  public function deleteAccount(Account $account): Response
  {
    $this->entityManager->remove($account);
    $this->entityManager->flush();

    return $this->referer->redirect(
      $this->redirectToRoute('accounts_index', [], 303)
    );
  }
}
