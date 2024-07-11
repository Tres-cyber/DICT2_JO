<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

$searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;

// Query to count total job orders
$countSql = "
    SELECT COUNT(*) as total
    FROM JobOrder jo
    LEFT JOIN Personnels pi ON jo.issued_by = pi.personnel_id
    LEFT JOIN Personnels pa ON jo.approved_by = pa.personnel_id
    LEFT JOIN Personnels pp ON jo.performer_id = pp.personnel_id
";

$countParams = [];

// Conditionally add WHERE clause for search query
if (!empty($searchQuery)) {
    $countSql .= " WHERE (jo.client_name LIKE :search_query OR
        jo.job_order_number LIKE :search_query OR
        pi.name LIKE :search_query OR
        pp.name LIKE :search_query OR
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

// Main query to get job orders
$sql = "
    SELECT
        *,
        pi.name AS issued_by,
        pa.name AS approved_by,
        pp.name as performed_by
    FROM JobOrder jo
    LEFT JOIN Personnels pi ON jo.issued_by = pi.personnel_id
    LEFT JOIN Personnels pa ON jo.approved_by = pa.personnel_id
    LEFT JOIN Personnels pp ON jo.performer_id = pp.personnel_id
";

// Conditionally add WHERE clause for search query
$params = [];
if (!empty($searchQuery)) {
    $sql .= " WHERE (jo.client_name LIKE :search_query OR
        jo.job_order_number LIKE :search_query OR
        pi.name LIKE :search_query OR
        pp.name LIKE :search_query OR
        jo.request_date LIKE :search_query OR
        jo.scheduled_start_date LIKE :search_query OR
        jo.scheduled_end_date LIKE :search_query OR
        jo.status LIKE :search_query OR
        pa.name LIKE :search_query)";
    $params[':search_query'] = '%' . $searchQuery . '%';
}

$sql .= " ORDER BY jo.job_order_id DESC"; // Always order by some column to ensure consistent results

$offset = 8 * ($p - 1);
$sql .= " LIMIT 8 OFFSET " . $offset;

$stmt = execute($sql, $params);
$job_orders = $stmt->fetchAll();

$startIndex = $offset + 1;
$endIndex = min($offset + count($job_orders), $totalCount);

echo $twig->render('job_orders.twig', [
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
