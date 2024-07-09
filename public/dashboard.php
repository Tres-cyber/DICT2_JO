<?php
require_once __DIR__ . '/app/setup.php';
$account = protectRoute();

$searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';


$sql = "
    SELECT
        *,
        pi.name AS issued_by,
        pa.name AS approved_by
    FROM JobOrder jo
    LEFT JOIN Personnels pi ON jo.issued_by = pi.personnel_id
    LEFT JOIN Personnels pa ON jo.approved_by = pa.personnel_id
    WHERE jo.performer_id = :personnel_id
";

// Add search condition if search query is provided
if (!empty($searchQuery)) {
    $sql .= " AND (jo.client_name LIKE :search_query OR
        jo.job_order_number LIKE :search_query OR
        pi.name LIKE :search_query OR
        jo.request_date LIKE :search_query OR
        jo.scheduled_start_date LIKE :search_query OR
        jo.scheduled_end_date LIKE :search_query OR
        jo.status LIKE :search_query OR
        pa.name LIKE :search_query)";
    $params = [
        ':personnel_id' => $account['personnel_id'],
        ':search_query' => '%' . $searchQuery . '%'
    ];
} else {
    $params = [
        ':personnel_id' => $account['personnel_id']
    ];
}

$stmt = execute($sql, $params);
$job_orders = $stmt->fetchAll();

echo $twig->render('dashboard.twig', ['joborders' => $job_orders, 'count' => count($job_orders)]);
