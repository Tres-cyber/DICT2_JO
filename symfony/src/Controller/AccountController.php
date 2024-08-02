<?php

namespace App\Controller;

use App\Entity\Personnel;
use App\Entity\Accounts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route('/admin/accounts', name: 'accounts_index', methods: ['GET'])]
    public function index(): Response
    {
         /** @var \App\Repository\PersonnelRepository */
         $personnelRepository = $this->entityManager->getRepository(Personnel::class);
         /** @var \App\Repository\AccountRepository */
         $accountRepository = $this->entityManager->getRepository(Personnel::class);

         $accounts = $accountRepository->findAllJoined();
         $personnels = $personnelRepository->findAllJoined();

        return $this->render('admin/accounts.twig', [
            'accounts' => $accounts,
            'personnels' => $personnels,
        ]);
    }
}
