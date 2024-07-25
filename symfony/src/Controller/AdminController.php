<?php

namespace App\Controller;

use App\Repository\AccountSessionRepository;
use App\Repository\PersonnelRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentToken;

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
        $projects= $projectRepository->findAll();
        
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
        $personnels= $personnelRepository->findAll();
        
        $options = array_map(function ($item) {
            return $item->getName();
        }, $personnels);

        return $this->render('admin/projects.twig', [
            'project' => $projects,
            'personnel' => $personnels,
            'options' => $options,
        ]);
    }
    
}
