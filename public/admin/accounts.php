<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

use App\Controllers\AccountsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

$session = new Session(new  PhpBridgeSessionStorage());
$request = Request::createFromGlobals();
$request->setSession($session);

$controller = new AccountsController();

if ($request->isMethod('POST')) {
  $req = $controller->create($request);
} else if ($request->query->has('logout')) {
  $req = $controller->logout($request);
} else if ($request->query->has('delete')) {
  $req = $controller->delete($request);
} else {
  $req = $controller->show($request);
}
$req->send();
