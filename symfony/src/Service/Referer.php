<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class Referer
{
  private ?string $referer = null;

  public function __construct(RequestStack $requestStack)
  {
    $request = $requestStack->getCurrentRequest();
    $this->referer = $request->headers->get('referer');
  }

  public function getReferer(): ?string
  {
    return $this->referer;
  }

  public function redirect(RedirectResponse $fallback): RedirectResponse
  {
    $fallback->setTargetUrl($this->referer);
    return $fallback;
  }
}
