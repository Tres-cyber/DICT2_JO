<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use App\Repository\PersonnelRepository;
use App\Service\Referer;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

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
    $search = strtolower($request->query->get('search'));

    $qb = $this->accountRepository->createJoinedQueryBuilder();
    $qb = $qb->andWhere("personnel.name LIKE :search OR (personnel IS NULL AND LOWER('admin') LIKE :search)")
      ->setParameter('search', '%' . $search . '%');

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

      try {
        $this->entityManager->flush();

        $this->addFlash('notifications', [
          'title' => 'Added account successfully',
          'message' => "Successfully added account '" . $account->getEmail() . "'"
        ]);
      } catch (UniqueConstraintViolationException $e) {
        $this->addFlash('notifications', [
          'title' => 'Cannot create account',
          'message' => "Email '" . $account->getEmail() . "' is already registered"
        ]);
      }

      return $this->referer->redirect(
        $this->redirectToRoute('accounts_index')
      );
    }

    if ($form->isSubmitted()) {
      $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
      return $this->renderBlock('personnels.twig', 'addStream', [
        'addForm' => $form,
      ]);
    }


    $page = $request->query->getInt('page', 1);
    $accounts = $paginator->paginate(
      $qb,
      $page,
      10
    );

    if ($request->query->has('table')) {
      $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
      return $this->renderBlock('accounts.twig', 'tableStream', [
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

    return $this->referer->redirect(
      $this->redirectToRoute('accounts_index')
    );
  }

  #[Route('/admin/accounts/{id}', name: 'account_delete', methods: ['DELETE'])]
  public function deleteAccount(Account $account): Response
  {
    $this->entityManager->remove($account);
    $this->entityManager->flush();

    return $this->referer->redirect(
      $this->redirectToRoute('accounts_index', [])
    );
  }

  #[Route('/admin/accounts/{id}/edit', name: 'account_edit', methods: ['GET'])]
  #[Route('/admin/accounts/{id}', name: 'account_update', methods: ['PUT'])]
  public function edit(Account $account, Request $request, UserPasswordHasherInterface $passwordHasher)
  {
    $form = $this->createForm(AccountType::class, $account, [
      'method' => 'PUT',
      'action' => $this->generateUrl('account_update', [
        'id' => $account->getId()
      ]),
      'is_editing' => true,
    ]);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $passwordHash = $passwordHasher->hashPassword(
        $account,
        $account->getPassword(),
      );
      $account->setPassword($passwordHash);
      $account->removeSession();

      $this->entityManager->flush();
      $this->addFlash('notifications', [
        'title' => 'Edited account successfully',
        'message' => "Successfully editted account '" . $account->getEmail() . "'"
      ]);

      return $this->referer->redirect(
        $this->redirectToRoute('accounts_index')
      );
    }

    $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
    return $this->renderBlock('accounts.twig', 'edit', [
      'editForm' => $form,
      'account' => $account,
    ]);
  }
}
