<?php

require_once __DIR__ . '/app/setup.php';

use App\Controllers\SiginController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

$controller = new SiginController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $session = new Session(new  PhpBridgeSessionStorage());
  $request = Request::createFromGlobals();

  $request->setSession($session);
  $response = $controller->signin($request);
} else {
  $response = $controller->show();
}

$response->send();
