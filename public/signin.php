<?php

require_once __DIR__ . '/app/setup.php';

use App\Controllers\SigninController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;


$session = new Session(new  PhpBridgeSessionStorage());
$request = Request::createFromGlobals();
$request->setSession($session);

$controller = new SigninController();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $response = $controller->signin($request);
} else {
  $response = $controller->show();
}

$response->send();
