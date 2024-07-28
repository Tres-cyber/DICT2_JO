<?php

namespace App\Controller;

use App\Entity\Personnel;
use App\Repository\AccountSessionRepository;
use App\Repository\JobOrderRepository;
use App\Repository\PersonnelRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
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

  #[Route('/admin/personnels', name: 'admin_personnels')]
  public function personnels(Request $request, PersonnelRepository $personnelRepository, ProjectRepository $projectRepository): Response
  {
    $personnels = $personnelRepository->findAllJoinedToProject();
    $projects = $projectRepository->findAll();

    $options = array_map(function ($item) {
      return $item->getName();
    }, $projects);

    return $this->render('admin/personnels.twig', [
      'personnel' => $personnels,
      'options' => $options,
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
  #[Route('/admin/personnels/create', name: 'admin_personnel_create', methods: ['POST'])]
  public function createPersonnel(EntityManagerInterface $entityManager, Request $request, ProjectRepository $projectRepository): Response
  {
    $projects = $projectRepository->findOneBy(['name' => $request->request->get('project_name')]);
    if (is_null($projects)) {

      return new Response("Project does not exist.", 400);
    }

    $personnel = new Personnel();
    $personnel->setName($request->request->get('name'));
    $personnel->setPosition($request->request->get('position'));
    $personnel->setProject($projects);

    $entityManager->persist($personnel);
    $entityManager->flush();

    $this->addFlash('success', 'Personnel created successfully.');
    return $this->redirectToRoute('admin_personnels');
  }

  #[Route('/admin/personnels/delete', name: 'admin_personnel_delete', methods: ['GET'])]
  public function deletePersonnel(EntityManagerInterface $entityManager, Request $request, PersonnelRepository $personnelRepository): Response
  {
    $personnel = $personnelRepository->find($request->query->get('id'));
    $personnel->setDeleted(true);
    $entityManager->flush();

    return  $this->redirectToRoute('admin_personnels');
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

