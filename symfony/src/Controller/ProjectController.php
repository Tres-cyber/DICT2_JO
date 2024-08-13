<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Service\Referer;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $entityManager,
    private ProjectRepository $projectRepository,
    private Referer $referer,
  ) {}

  #[Route('/admin/projects', name: 'projects_index', methods: ['GET', 'POST'])]
  public function projects(PaginatorInterface $paginator, Request $request): Response
  {
    $project = new Project();

    $form = $this->createForm(ProjectType::class, $project);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->entityManager->persist($project);
      $this->entityManager->flush();
      $this->addFlash('notifications', [
        'title' => 'Added project successfully',
        'message' => "Successfully added project '" . $project->getName() . "'"
      ]);

      return $this->referer->redirect(
        $this->redirectToRoute('projects_index', [], 303)
      );
    }

    $search = strtolower($request->query->get('search'));
    $qb = $this->projectRepository->createJoinedQueryBuilder();
    $qb->andWhere('project.is_deleted = 0')
      ->andWhere($qb->expr()->orX(
        'LOWER(project.code) LIKE :search',
        'LOWER(project.name) LIKE :search',
        'LOWER(focal_person.name) LIKE :search',
      ))->setParameter(':search', '%' . $search .  '%')
      ->orderBy('project.name', 'ASC');

    $projects = $paginator->paginate(
      $qb,
      $request->query->getInt('page', 1),
      10
    );

    return $this->render('projects.twig', [
      'addForm' => $form,
      'projects' => $projects,
      'search' => $search,
    ]);
  }

  #[Route('/admin/projects/{id}/edit', name: 'project_edit', methods: ['GET'])]
  #[Route('/admin/projects/{id}', name: 'project_update', methods: ['PUT'])]
  public function edit(Project $project, Request $request)
  {
    $oldLogo = $project->getLogo();
    $form = $this->createForm(ProjectType::class, $project, [
      'method' => 'PUT',
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if (is_null($project->getLogo())) {
        $project->setLogo($oldLogo);
      }

      $this->entityManager->flush();
      $this->addFlash('notifications', [
        'title' => 'Edited project successfully',
        'message' => "Successfully editted project '" . $project->getName() . "'"
      ]);
      return $this->referer->redirect(
        $this->redirectToRoute('projects_index', [], 303)
      );
    }

    return $this->render('projects_edit.twig', [
      'editForm' => $form->createView(),
      'project' => $project,
    ]);
  }

  #[Route('/admin/projects/{id}', name: 'project_delete', methods: ['DELETE'])]
  public function delete(Project $project)
  {
    $project->setDeleted(true);
    $this->entityManager->flush();

    $this->addFlash('notifications', [
      'title' => 'Deleted project successfully',
      'message' => "Successfully deleted project '" . $project->getName() . "'"
    ]);
    return $this->referer->redirect(
      $this->redirectToRoute('projects_index', [], 303)
    );
  }
}
