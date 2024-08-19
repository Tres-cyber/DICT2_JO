<?php

namespace App\Controller;

use App\Entity\JobOrder;
use App\Entity\JobOrderStatus;
use App\Form\JoborderType;
use App\Repository\JobOrderRepository;
use App\Repository\PersonnelRepository;
use App\Service\FormUtils;
use App\Service\Referer;
use App\Service\Uniqid;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;

class JoborderController extends AbstractController
{
  public function __construct(
    private EntityManagerInterface $entityManager,
    private JobOrderRepository $jobOrderRepository,
    private PersonnelRepository $personnelRepository,
    private Referer $referer,
    private FormUtils $formUtils,
  ) {}

  #[Route('/admin/joborders', name: 'admin_joborders', methods: ['GET'])]
  #[Route('/joborders', name: 'user_dashboard', methods: ['GET'])]
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
      ->orWhere('LOWER(joborder.control_number) LIKE :search')
      ->orderBy('joborder.archived_at');

    if ($isAdmin) {
      $qb = $qb->orWhere('LOWER(performer.name) LIKE :search');
    }

    if (!$isAdmin) {
      $qb = $qb->andWhere('joborder.performer = :performer')
        ->andWhere('joborder.archived_at IS NULL')
        ->setParameter(':performer', $account->getPersonnel());
    }

    $qb = $qb->setParameter(':search', '%' . $search . '%');

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


  #[Route('/joborders/{control_number}', name: 'joborder_view', methods: ['GET'], priority: -10)]
  public function view(
    #[MapEntity(mapping: ['control_number' => 'control_number'])]
    JobOrder $joborder,
  ) {
    /** @var \App\Entity\Account */
    $account = $this->getUser();

    $personnel = $account->getPersonnel();
    if (!$account->isAdmin() && $personnel->getId() != $joborder->getPerformer()->getId()) {
      return $this->createNotFoundException();
    }

    return $this->render('print_joborder.twig', [
      'jo' => $joborder,
    ]);
  }


  #[Route('/admin/joborders/create', name: 'admin_joborder_create', methods: ['GET', 'POST'])]
  #[Route('/joborders/create', name: 'user_joborder_create', methods: ['GET', 'POST'])]
  public function add(Request $request, Uniqid $uniqid)
  {
    $joborder = new JobOrder();
    $route = $request->attributes->get('_route');

    /** @var \App\Entity\Account */
    $account = $this->getUser();

    $performer = $account->getPersonnel();
    if (
      $account->isAdmin()
      && $route == "admin_joborder_create"
      && $request->query->has('personnel')
    ) {
      $id = $request->query->get('personnel');
      /** @var ?\App\Entity\Personnel */
      $performer = $this->personnelRepository->find($id);
      if (is_null($performer)) {
        throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY);
      }
    }

    $project = $performer->getProject();

    if (is_null($project)) {
      $this->addFlash('notifications', [
        'title' => 'Cannot create joborder',
        'message' => "You are not allowed to create a job order unless assigned to a project. Contact your admin to assign you to a project"
      ]);

      return $this->redirectToRoute('app_front', [], Response::HTTP_SEE_OTHER);
    }

    $director = $this->personnelRepository->getDirector();

    $joborder->setPerformer($performer);
    $joborder->addEndorsee($performer);
    $joborder->setProject($project);
    $joborder->setIssuer($project->getFocalPerson());
    $joborder->setApprover($director);

    $form = $this->createForm(JoborderType::class, $joborder);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if ($this->formUtils->isClicked('submit')) {
        $controlNumber = $this->jobOrderRepository->generateControlNumber($joborder);
        $joborder->submit($controlNumber);

        $this->entityManager->persist($joborder);
        $this->entityManager->flush();

        $this->addFlash('notifications', [
          'title' => "Submitted new joborder",
          'message' => "Saved joborder as '$controlNumber'",
        ]);
      } else if ($this->formUtils->isClicked('draft')) {
        $id = $uniqid->generate('DRAFT-');
        $joborder->setControlNumber($id);
        $joborder->setStatus(JobOrderStatus::Draft);

        $this->entityManager->persist($joborder);
        $this->entityManager->flush();

        $this->addFlash('notifications', [
          'title' => "Saved joborder as draft",
          'message' => "Saved joborder as draft with id '$id'",
        ]);
      }

      return $this->redirectToRoute('app_front', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('create_joborder.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/joborders/draft/{control_number}/edit', name: 'joborder_edit_draft', methods: ['GET'])]
  #[Route('/joborders/{control_number}/edit', name: 'joborder_edit', methods: ['GET'])]
  #[Route('/joborders/{control_number}', name: 'joborder_edit_save', methods: ['POST'])]
  public function edit(
    Request $request,
    #[MapEntity(mapping: ['control_number' => 'control_number'])]
    JobOrder $joborder,
  ) {
    /** @var \App\Entity\Account */
    $account = $this->getUser();

    if ($request->attributes->get('_route') == 'joborder_edit' and $joborder->getStatus() == 'DRAFT') {
      return $this->redirectToRoute('joborder_edit_draft', [
        'control_number' => $joborder->getControlNumber()
      ]);
    }

    $personnel = $account->getPersonnel();
    if (!$account->isAdmin() && $personnel->getId() != $joborder->getPerformer()->getId()) {
      return $this->createAccessDeniedException();
    }

    $form = $this->createForm(JoborderType::class, $joborder, [
      'action' => $this->generateUrl('joborder_edit_save', [
        'control_number' => $joborder->getControlNumber()
      ])
    ]);
    $form->handleRequest($request);
    $this->formUtils->setForm($form);

    if ($form->isSubmitted() && $form->isValid()) {
      if ($this->formUtils->isClicked('submit')) {
        $controlNumber = $this->jobOrderRepository->generateControlNumber($joborder);
        $joborder->submit($controlNumber);

        $this->entityManager->persist($joborder);
        $this->entityManager->flush();

        $this->addFlash('notifications', [
          'title' => "Submitted new joborder",
          'message' => "Saved joborder as '$controlNumber'",
        ]);
      } else if ($this->formUtils->isClicked('complete')) {
        $joborder->setStatus(JobOrderStatus::Completed);

        $this->entityManager->flush();

        $this->addFlash('notifications', [
          'title' => "Joborder is marked completed",
          'message' => "Joborder with control number '"
            . $joborder->getControlNumber() . "' is marked completed",
        ]);
      } else if ($this->formUtils->isClicked('draft')) {
        $this->entityManager->flush();
        $id = $joborder->getControlNumber();

        $this->addFlash('notifications', [
          'title' => "Saved edits",
          'message' => "Edited joborder with id '$id'",
        ]);
      }

      return $this->redirectToRoute('app_front', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('create_joborder.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/admin/joborders/{control_number}/archive', name: 'admin_joborder_archive', methods: ['PUT'])]
  public function archive(
    #[MapEntity(mapping: ['control_number' => 'control_number'])]
    JobOrder $jobOrder
  ): Response {
    $jobOrder->setArchivedAt(new DateTimeImmutable());
    $this->entityManager->flush();

    return $this->referer->redirect(
      $this->redirectToRoute('app_front')
    );
  }

  #[Route('/admin/joborders/{control_number}/unarchive', name: 'admin_joborder_unarchive', methods: ['PUT'])]
  public function unarchive(
    #[MapEntity(mapping: ['control_number' => 'control_number'])]
    JobOrder $jobOrder
  ): Response {
    $jobOrder->setArchivedAt(null);
    $this->entityManager->flush();

    return $this->referer->redirect(
      $this->redirectToRoute('app_front')
    );
  }
}
