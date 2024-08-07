<?php
require_once __DIR__ . '/app/setup.php';
$account = protectRoute();

use App\Controllers\FormController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

$session = new Session(new  PhpBridgeSessionStorage());
$request = Request::createFromGlobals();
$request->setSession($session);

$controller = new FormController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $resp  = $controller->create($request);
} else if (isset($_GET['delete'])) {
  $resp  = $controller->delete($request);
} else if (isset($_GET['id'])) {
  $resp = $controller->show($request);
} else {
  $resp = $controller->new($request);
}
$resp->send();
