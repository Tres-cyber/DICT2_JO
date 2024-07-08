<?php

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

function protectRoute($admin = false)
{
  $session = new Session(new PhpBridgeSessionStorage());
  if (!$session->has('session_id')) {
    header('Location: /signin.php');
    exit();
  }

  $session_id = $session->get('session_id');

  $stmt = execute("SELECT account_id, personnel_id, admin FROM Accounts WHERE current_session_id = :session_id AND deleted = 0", [':session_id' => $session_id]);
  $account = $stmt->fetch();

  if (!$account) {
    header('Location: /signin.php');
    exit();
  }

  if ($admin && !$account['admin']) {
    $_SESSION['signin_err'] = 'Unauthorized path';
    header('Location: /signin.php');
    exit();
  }

  return $account;
}
