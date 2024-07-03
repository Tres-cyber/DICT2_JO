<?php
require_once __DIR__ . '/app/setup.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $stmt = execute("SELECT account_id, current_session_id, password_hash, admin FROM Accounts WHERE email = :email AND deleted = 0", [
    'email' => $_POST['email'],
  ]);
  $account = $stmt->fetch();

  if (!$account) {
    $_SESSION['signin_err'] = 'Invalid email or password';
    header('Location: /signin.php');
    exit();
  }

  if (!is_null($account['current_session_id'])) {
    $_SESSION['signin_err'] = 'Account is already logged in';
    header('Location: /signin.php');
    exit();
  }

  if (!password_verify($_POST['password'], $account['password_hash'])) {
    $_SESSION['signin_err'] = 'Invalid email or password';
    header('Location: /signin.php');
    exit();
  }

  $stmt = execute("INSERT INTO Sessions (account_id, login) VALUES (:account_id, NOW())", [':account_id' => $account['account_id']]);
  $newSessionId = getDB()->lastInsertId();

  execute(
    "UPDATE Accounts SET current_session_id = :new_session_id WHERE account_id = :account_id",
    [
      ':new_session_id' => $newSessionId,
      ':account_id' => $account['account_id']
    ]
  );

  unset($_SESSION['signin_err']);
  $_SESSION['session_id'] = $newSessionId;
  if ($account['admin'] && is_null($account['account_id'])) {
    header('Location: /admin/');
  } else {
    header('Location: /dashboard.php');
  }
  exit();
} else {
  echo $twig->render('signin.twig', []);
  unset($_SESSION['signin_err']);
}
