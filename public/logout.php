<?php

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

require_once __DIR__ . '/app/setup.php';

$session = new Session(new PhpBridgeSessionStorage());

if (!$session->has('session_id')) {
  header('Location: /signin.php');
  exit();
}

$session_id = $session->get('session_id');

$stmt = execute("SELECT account_id FROM Accounts WHERE current_session_id = :session_id", [':session_id' => $session_id]);
$account = $stmt->fetch();

execute("UPDATE Accounts SET current_session_id = NULL WHERE account_id = :account_id", [':account_id' => $account['account_id']]);
execute("UPDATE Sessions SET logout = NOW() WHERE session_id = :session_id", [':session_id' => $session_id]);

$session->remove('session_id');
header('Location: /signin.php');
exit();
