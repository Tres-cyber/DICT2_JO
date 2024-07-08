<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

$sql = "
        SELECT
            *,
            pa.name
        FROM Sessions ses
        JOIN Accounts acc ON ses.account_id = acc.account_id
        LEFT JOIN Personnels pa ON acc.personnel_id = pa.personnel_id
        ORDER by ses.session_id DESC
";

$stmt = execute($sql);
$session = $stmt->fetchAll();
echo $twig->render('activity-log.twig', ['session' => $session, 'count' => count($session)]);
