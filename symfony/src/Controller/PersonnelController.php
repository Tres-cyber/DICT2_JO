<?php

namespace App\Controller;

use App\Entity\Personnel;
use App\Form\PersonnelType;
use App\Repository\PersonnelRepository;
use App\Service\Referer;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PersonnelController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $entityManager,
    private PersonnelRepository $personnelRepository,
    private Referer $referer,
  ) {}

  #[Route('/admin/personnels', name: 'personnels_index', methods: ['GET', 'POST'])]
  public function index(PaginatorInterface $paginator, Request $request): Response
  {
    $search = strtolower($request->query->getString('search', ''));
    $qb = $this->personnelRepository->createJoinedQueryBuilder();
    $qb->andWhere('personnel.is_deleted = 0')
      ->andWhere($qb->expr()->orX(
        'LOWER(personnel.name) LIKE :search',
        'LOWER(project.name) LIKE :search',
      ))->setParameter(':search', '%' . $search .  '%');

    $personnels = $paginator->paginate(
      $qb,
      $request->query->getInt('page', 1),
      10
    );

    $personnel = new Personnel();
    $form = $this->createForm(PersonnelType::class, $personnel);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->entityManager->persist($personnel);
      $this->entityManager->flush();
      $this->addFlash('notifications', [
        'title' => 'Added personnel successfully',
        'message' => "Successfully added personnel '" . $personnel->getName() . "'"
      ]);

      return $this->referer->redirect(
        $this->redirectToRoute('personnels_index', [], 303)
      );
    }

    return $this->render('personnels.twig', [
      'addForm' => $form->createView(),
      'personnels' => $personnels,
      'search' => $search,
    ]);
  }

  #[Route('/admin/personnels/{id}/edit', name: 'personnel_edit', methods: ['GET'])]
  #[Route('/admin/personnels/{id}', name: 'personnel_update', methods: ['PUT'])]
  public function edit(Personnel $personnel, Request $request)
  {
    $form = $this->createForm(PersonnelType::class, $personnel, [
      'method' => 'PUT',
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->entityManager->flush();
      $this->addFlash('notifications', [
        'title' => 'Edited personnel successfully',
        'message' => "Successfully editted personnel '" . $personnel->getName() . "'"
      ]);
      return $this->referer->redirect(
        $this->redirectToRoute('personnels_index', [], 303)
      );
    }

    return $this->render('personnels_edit.twig', [
      'editForm' => $form->createView(),
      'personnel' => $personnel,
    ]);
  }

  #[Route('/admin/personnels/{id}', name: 'personnel_delete', methods: ['DELETE'])]
  public function delete(Personnel $personnel, Request $request): Response
  {
    $personnel->setDeleted(true);
    $this->entityManager->flush();

    $this->addFlash('notifications', [
      'title' => 'Deleted personnel successfully',
      'message' => "Successfully deleted personnel '" . $personnel->getName() . "'"
    ]);
    return $this->referer->redirect(
      $this->redirectToRoute('personnels_index', [], 303)
    );
  }
}
