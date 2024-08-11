<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityManagerInterface;
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
  ) {}

  #[Route('/admin/accounts', name: 'accounts_index', methods: ['GET'])]
  public function index(): Response
  {
    $personnels = $this->personnelRepository->findBy([
      'is_deleted' => 0,
    ]);
    $accounts = $this->accountRepository->findAllJoined();

    return $this->render('accounts.twig', [
      'personnels' => $personnels,
      'accounts' => $accounts,
    ]);
  }

  #[Route('/admin/accounts', name: 'account_add', methods: ['POST'])]
  public function add(Request $request, UserPasswordHasherInterface $hasher, LoggerInterface $logger): Response
  {
    $account = new Account();

    $personnel = $this->personnelRepository->findOneBy([
      'id' => $request->request->getInt('personnel'),
      'is_deleted' => 0,
    ]);

    if (is_null($personnel)) {
      throw new BadRequestException("Personnel doesn't correspond to any active user");
    }

    $account->setPersonnel($personnel);
    $account->setEmail($request->request->getString('email'));

    $hashedPassword = $hasher->hashPassword(
      $account,
      $request->request->getString('password'),
    );
    $logger->error($request->request->getString('password'));
    $account->setPassword($hashedPassword);

    $this->entityManager->persist($account);
    $this->entityManager->flush();

    return $this->redirectToRoute('accounts_index');
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

    return $this->redirectToRoute('accounts_index');
  }
}
