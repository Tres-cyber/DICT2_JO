<?php

namespace App\Controller;

use App\Repository\JobOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JoborderController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $entityManager,
    private JobOrderRepository $jobOrderRepository
  ) {}

  #[Route('/admin/joborders', name: 'admin_joborders')]
  #[Route('/joborders', name: 'user_dashboard')]
  public function index(PaginatorInterface $paginator, Request $request): Response
  {
    /** @var \App\Entity\Account */
    $account = $this->getUser();
    $isAdmin = $request->attributes->get('_route') == 'admin_joborders';


    if (!$isAdmin and is_null($account->getPersonnel())) {
      return $this->redirectToRoute('admin_joborders');
    }

    $search = strtolower($request->query->getString('search', ''));

    $qb = $this->jobOrderRepository->createJoinedQueryBuilder();
    $qb = $qb->orWhere('LOWER(joborder.client_name) LIKE :search')
      ->orWhere('LOWER(joborder.control_number) LIKE :search');

    if ($account->isAdmin()) {
      $qb = $qb->orWhere('LOWER(performer.name) LIKE :search');
    }
    $qb = $qb->setParameter(':search', $search);

    if (!is_null($account->getPersonnel())) {
      $qb = $qb->andWhere('joborder.performer = :performer')
        ->setParameter(':performer', $account);
    }

    $jobOrders = $paginator->paginate(
      $qb,
      $request->query->getInt('page', 1),
      10
    );

    return $this->render('joborders.twig', [
      'jobOrders' => $jobOrders,
      'search' => $search,
      'isAdmin' => $isAdmin,
    ]);
  }
}
