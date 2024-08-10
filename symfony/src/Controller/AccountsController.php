<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountsController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $entityManager,
    private PersonnelRepository $personnelRepository,
    private AccountRepository $accountRepository,
  ) {}

  #[Route('/admin/accounts', name: 'accounts_index', methods: ['GET'])]
  public function index(): Response
  {
    $peronnels = $this->personnelRepository->findAll();
    $accounts = $this->accountRepository->findAllJoined();

    return $this->render('accounts.twig', [
      'personnels' => $peronnels,
      'accounts' => $accounts,
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
    return $this->redirectToRoute('accounts_index');
  }
}
