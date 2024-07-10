<?php

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

function getUser(): ?array
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

function protectRoute($admin = false)
{
  $account = getUser() ?? false;

  if (!$account) {
    header('Location: /signin.php');
    exit();
  }

  if ($admin && !$account['admin']) {
    header('Location: /signin.php');
    exit();
  }

  return $account;
}
