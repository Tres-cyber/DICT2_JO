<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

$sql = "
        SELECT
            *,
            pa.name
        FROM Accounts acc
        JOIN Personnels pa ON acc.personnel_id = pa.personnel_id
        WHERE acc.deleted = false
";

$stmt = execute($sql);
$account = $stmt->fetchAll();
echo $twig->render('accounts.twig', ['account' => $account, 'count' => count($account)]);
 