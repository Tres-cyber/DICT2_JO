<?php

use App\Util\Env;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/jobOrder.php';

session_start();

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views');
$twig = new \Twig\Environment($loader, [
  'debug' => \App\Util\Env::isDev(),
  'autoescape' => 'html',
]);


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
$app->user = getUser();
$twig->addGlobal('app', $app);
if (\App\Util\Env::isDev()) $twig->addExtension(new \Twig\Extension\DebugExtension());
$twig->addFunction(new \Twig\TwigFunction('vite', [\App\Util\Vite::class, 'vite']));
