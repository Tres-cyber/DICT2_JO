<?php

namespace App\Twig\Components;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class NavBar
{
  private string $route;

  public function __construct(private RequestStack $requestStack)
  {

    /** @var \Symfony\Component\HttpFoundation\Request */
    $request = $requestStack->getCurrentRequest();
    $this->route = $request->attributes->get('_route');
  }

  public function getRoute(): string
  {
    return $this->route;
  }
}
