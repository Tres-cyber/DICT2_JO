<?php
require_once __DIR__ . '/app/setup.php';
$account = protectRoute();

$searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;


$countSql = "
    SELECT COUNT(*) as total
    FROM JobOrder jo
    LEFT JOIN Personnels pi ON jo.issued_by = pi.personnel_id
    LEFT JOIN Personnels pa ON jo.approved_by = pa.personnel_id
    WHERE jo.performer_id = :personnel_id
";

$countParams = [
    ':personnel_id' => $account['personnel_id']
];

if (!empty($searchQuery)) {
    $countSql .= " AND (jo.client_name LIKE :search_query OR
        jo.job_order_number LIKE :search_query OR
        pi.name LIKE :search_query OR
        jo.request_date LIKE :search_query OR
        jo.scheduled_start_date LIKE :search_query OR
        jo.scheduled_end_date LIKE :search_query OR
        jo.status LIKE :search_query OR
        pa.name LIKE :search_query)";
    $countParams[':search_query'] = '%' . $searchQuery . '%';
}

$countStmt = execute($countSql, $countParams);
$totalCount = $countStmt->fetchColumn();
$totalPages = ceil($totalCount / 8);


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

$params = [
    ':personnel_id' => $account['personnel_id']
];

if (!empty($searchQuery)) {
    $sql .= " AND (jo.client_name LIKE :search_query OR
        jo.job_order_number LIKE :search_query OR
        pi.name LIKE :search_query OR
        jo.request_date LIKE :search_query OR
        jo.scheduled_start_date LIKE :search_query OR
        jo.scheduled_end_date LIKE :search_query OR
        jo.status LIKE :search_query OR
        pa.name LIKE :search_query)";
    $params[':search_query'] = '%' . $searchQuery . '%';
}

$offset = 8 * ($p - 1);
$sql .= " LIMIT 8 OFFSET " . $offset;

$stmt = execute($sql, $params);
$job_orders = $stmt->fetchAll();


$startIndex = $offset + 1;
$endIndex = min($offset + count($job_orders), $totalCount);

echo $twig->render('dashboard.twig', [
    'joborders' => $job_orders,
    'count' => count($job_orders),
    'current_page' => $p,
    'total_pages' => $totalPages,
    'search_query' => $searchQuery,
    'total_count' => $totalCount,
    'start_index' => $startIndex,  
    'end_index' => $endIndex       
]);
?>
