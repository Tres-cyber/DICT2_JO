<?php

namespace App\Controller;

use App\Repository\AccountSessionRepository;
use App\Repository\JobOrderRepository;
use App\Repository\PersonnelRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
  #[Route('/admin/activities', name: 'admin_activities')]
  public function activities(Request $request, AccountSessionRepository $sessionRepository): Response
  {
    $sessions = $sessionRepository->findAll();

    return $this->render('admin/activities.twig', [
      'session' => $sessions
    ]);
  }

  #[Route('/admin/projects', name: 'admin_projects')]
  public function projects(Request $request, PersonnelRepository $personnelRepository, ProjectRepository $projectRepository): Response
  {
    $projects = $projectRepository->findAllJoinedToPersonnel();
    $personnels = $personnelRepository->findAll();

    $options = array_map(function ($item) {
      return $item->getName();
    }, $personnels);

    return $this->render('admin/projects.twig', [
      'project' => $projects,
      'personnel' => $personnels,
      'options' => $options,
    ]);
  }

  #[Route('/admin/job_orders', name: 'admin_joborders')]
  public function joborders(Request $request, JobOrderRepository $JobOrderRepository): Response
  {
    $JobOrders = $JobOrderRepository->findAll();

    return $this->render('admin/job_orders.twig', [
      'joborders' => $JobOrders,
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
