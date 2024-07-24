<?php

namespace App\Controller;

use App\Repository\JobOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(JobOrderRepository $jobOrderRepository): Response
    {
        return $this->render('dashboard.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
