<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

if (empty($_GET['id'])){
    header('Location: /admin/accounts.php');  
    exit();
}

$account_id = $_GET['id'];

$stmt = execute("SELECT account_id FROM Accounts WHERE account_id = :account_id", [':account_id' => $account_id]);
$account = $stmt->fetch();


execute("UPDATE Accounts SET deleted = 1 WHERE account_id = :account_id", [':account_id' => $account['account_id']]);



header('Location: /admin/accounts.php');
exit();
