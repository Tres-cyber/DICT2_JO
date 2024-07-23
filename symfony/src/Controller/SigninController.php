<?php

namespace App\Controller;

use App\Form\Type\SigninType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SigninController extends AbstractController
{
  #[Route('/signin')]
  public function show(): Response
  {

    $form = $this->createForm(SigninType::class);
    return $this->render('signin.twig', ['form' => $form->createView()]);
  }
}
