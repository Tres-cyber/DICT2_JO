<?php
require_once __DIR__ . '/app/setup.php';
$account = protectRoute();

$sql = "
        SELECT
            *,
            pi.name AS issued_by,
            pa.name AS approved_by
        FROM JobOrder jo
        JOIN Personnels pi ON jo.issued_by = pi.personnel_id
        JOIN Personnels pa ON jo.approved_by = pa.personnel_id
        WHERE jo.performer_id = :personnel_id
";

$stmt = execute($sql, [':personnel_id' => $account['personnel_id']]);
$job_orders = $stmt->fetchAll();

echo $twig->render('dashboard.twig', ['joborders' => $job_orders, 'count' => count($job_orders)]);
