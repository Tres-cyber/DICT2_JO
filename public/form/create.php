<?php
require_once __DIR__ . '/../app/setup.php';

use App\Controllers\FormController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

$session = new Session(new  PhpBridgeSessionStorage());
$request = Request::createFromGlobals();
$request->setSession($session);

$controller = new FormController();

$response = $controller->create($request);
$response->send();
