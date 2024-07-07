<?php

namespace App\Controllers;

use App\Util\Env;
use App\Util\Vite;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Twig\TwigFunction;

abstract class BaseController
{
  protected $twig;

  public function __construct()
  {
    $loader = new FilesystemLoader(__DIR__ . '/../../views');
    $this->twig = new Environment($loader, [
      'debug' => Env::isDev(),
      'autoescape' => 'html',
    ]);

    if (Env::isDev()) $this->twig->addExtension(new DebugExtension());
    $this->twig->addFunction(new TwigFunction('vite', [Vite::class, 'vite']));
    $this->twig->addExtension(new FormExtension());

    $formEngine = new TwigRendererEngine(['form_div_layout.html.twig'], $this->twig);
    $formRenderer = new FormRenderer($formEngine);
    $this->twig->addRuntimeLoader(new FactoryRuntimeLoader([
      FormRenderer::class => function () use ($formRenderer) {
        return $formRenderer;
      },
    ]));

    $app = new class {
      public $session = null;
      public function __construct()
      {
        $this->session = new Session(new PhpBridgeSessionStorage());
      }

      public function flashes(string $name)
      {
        return $this->session->getFlashBag()->get($name);
      }
    };
    $this->twig->addGlobal('app', $app);
  }

  protected function render($name, array $context = [], Response $response = null): Response
  {
    $response ??= new Response();
    $content = $this->twig->render($name, $context);
    $response->setContent($content);
    return $response;
  }
}
