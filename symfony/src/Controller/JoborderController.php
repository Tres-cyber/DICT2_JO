<?php

namespace App\Controller;

use App\Entity\JobOrder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JoborderController extends AbstractController
{
  #[Route('/dashboard', name: 'user_dashboard')]
  public function index(EntityManagerInterface $entityManager): Response
  {
    $joborderRepository = $entityManager->getRepository(JobOrder::class);
    $jobOrders = $joborderRepository->findAll();

    return $this->render('dashboard.twig', [
      'joborders' => $jobOrders,
      'count' => 10,
      'current_page' => 1,
      'total_pages' => 5,
      'search_query' => '',
      'total_count' => 100,
      'start_index' => 1,
      'end_index' => 10,
      'sort_option' => 'date_asc'
    ]);
  }
}
