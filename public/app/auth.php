<?php

function protectRoute($admin = false)
{
  if (!isset($_SESSION['session_id'])) {
    header('Location: /signin.php');
    exit();
  }

  $session_id = $_SESSION['session_id'];

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
