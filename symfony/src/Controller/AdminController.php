<?php

namespace App\Controller;

use App\Repository\AccountSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/activities', name: 'app_admin')]
    public function activities(Request $request, AccountSessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findAll();
        
        return $this->render('admin/activities.twig', [
            'session' => $sessions
        ]);
    }
}
