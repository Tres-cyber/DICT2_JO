<?php
require_once __DIR__ . '/app/setup.php';

if (!isset($_SESSION['session_id'])) {
  header('Location: /signin.php');
  exit();
}

$session_id = $_SESSION['session_id'];

$stmt = execute("SELECT account_id FROM Accounts WHERE current_session_id = :session_id", [':session_id' => $session_id]);
$account = $stmt->fetch();

execute("UPDATE Accounts SET current_session_id = NULL WHERE account_id = :account_id", [':account_id' => $account['account_id']]);
execute("UPDATE Sessions SET logout = NOW() WHERE session_id = :session_id", [':session_id' => $session_id]);

unset($_SESSION['session_id']);
header('Location: /signin.php');
exit();
