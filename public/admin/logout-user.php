<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

if (empty($_GET['id'])) {
  header('Location: /admin/accounts.php');
  exit();
}
$session_id = $_GET['id'];

$stmt = execute("SELECT account_id FROM Accounts WHERE current_session_id = :session_id", [':session_id' => $session_id]);
$account = $stmt->fetch();

execute("UPDATE Accounts SET current_session_id = NULL WHERE account_id = :account_id", [':account_id' => $account['account_id']]);
execute("UPDATE Sessions SET logout = NOW() WHERE session_id = :session_id", [':session_id' => $session_id]);

header('Location: /admin/accounts.php');
exit();
