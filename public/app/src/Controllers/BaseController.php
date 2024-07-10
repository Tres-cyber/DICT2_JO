<?php

namespace App\Controllers;

use App\Util\Env;
use App\Util\Vite;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Validation;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Twig\TwigFunction;

abstract class BaseController
{
  protected $twig;
  protected $formFactory;

  public function __construct()
  {
    $csrfGenerator = new UriSafeTokenGenerator();
    $csrfStorage = new NativeSessionTokenStorage();
    $csrfManager = new CsrfTokenManager($csrfGenerator, $csrfStorage);

    $appVariableReflection = new \ReflectionClass('\Symfony\Bridge\Twig\AppVariable');
    $vendorTwigBridgeDirectory = dirname($appVariableReflection->getFileName());
    $viewsDirectory  = realpath(__DIR__ . '/../../views');

    $loader = new FilesystemLoader([
      $viewsDirectory,
      $vendorTwigBridgeDirectory . '/Resources/views/Form'
    ]);
    $this->twig = new Environment($loader, [
      'debug' => Env::isDev(),
      'autoescape' => 'html',
    ]);

    $translator = new Translator('en');

    if (Env::isDev()) $this->twig->addExtension(new DebugExtension());
    $this->twig->addFunction(new TwigFunction('vite', [Vite::class, 'vite']));
    $this->twig->addExtension(new FormExtension());
    $this->twig->addExtension(new TranslationExtension($translator));


    $formEngine = new TwigRendererEngine(['bootstrap_5_layout.html.twig'], $this->twig);
    $this->twig->addRuntimeLoader(new FactoryRuntimeLoader([
      FormRenderer::class => function () use ($formEngine, $csrfManager) {
        return new FormRenderer($formEngine, $csrfManager);
      },
    ]));

    $app = new class {
      public $session = null;
      public $user = null;
      public $request = null;
      public $debug = false;

      public function __construct()
      {
        $this->session = new Session(new PhpBridgeSessionStorage());
        $this->request = Request::createFromGlobals();
        $this->request->setSession($this->session);
        $this->debug = Env::isDev();
      }

      public function flashes(string $name)
      {
        return $this->session->getFlashBag()->get($name);
      }
    };
    $app->user = $this->getUser();
    $this->twig->addGlobal('app', $app);

    $validator = Validation::createValidator();

    $this->formFactory = Forms::createFormFactoryBuilder()
      ->addExtension(new HttpFoundationExtension())
      ->addExtension(new CsrfExtension($csrfManager))
      ->addExtension(new ValidatorExtension($validator))
      ->getFormFactory();
  }

  protected function render($name, array $context = [], Response $response = null): Response
  {
    $response ??= new Response();
    $content = $this->twig->render($name, $context);
    $response->setContent($content);
    return $response;
  }

  protected function createForm(string $type, mixed $data = null, array $options = []): FormInterface
  {
    return $this->formFactory->create($type, $data, $options);
  }

  protected function getUser(): ?array
  {
    static $account = null;

    $session = new Session(new PhpBridgeSessionStorage());
    if ($account  === null) {
      if (!$session->has('session_id')) {
        return null;
      }

      $session_id = $session->get('session_id');

      $stmt = execute("
      SELECT 
        acc.account_id, acc.personnel_id, acc.admin,
        per.name, per.position, per.project_id
      FROM Accounts acc
      LEFT JOIN Personnels per ON acc.personnel_id = per.personnel_id
      WHERE 
        current_session_id = :session_id AND deleted = 0
    ", [':session_id' => $session_id]);
      $account = $stmt->fetch();
    }

    if (!$account) {
      return null;
    }

    return $account;
  }

  protected function allowRoles($role = 'any'): ?array
  {
    $account = $this->getUser();

    if (!$account) {
      header('Location: /signin.php');
      exit();
    }

    if ($role == 'admin' && !$account['admin']) {
      header('Location: /signin.php');
      exit();
    }

    return $account;
  }
}
