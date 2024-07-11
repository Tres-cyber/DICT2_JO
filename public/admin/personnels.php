<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
  $sql = "
  INSERT INTO Personnels (
      name, position ,project_id
  ) VALUES (
      :name, :position, :project_id
  );";
  
  $id = execute("SELECT project_id FROM Projects WHERE project_name = :n", ['n' => $_POST['project_name']])->fetchColumn();
  if (!$id) $id = null;

  $args = [
      ':project_id' => $id,
      ':name' => $_POST['name'],
      ':position' => $_POST['position'],
  ];
  
  execute($sql,$args);
  
  header('Location: /admin/personnels.php');
  exit();
  
  }
$searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;


$countSql = "
    SELECT COUNT(*) as total
    FROM Personnels per
    LEFT JOIN Projects proj ON per.project_id = proj.project_id
";

$countParams = [];


if (!empty($searchQuery)) {
    $countSql .= " WHERE per.name LIKE :search_query
                   OR per.position LIKE :search_query
                   OR proj.project_name LIKE :search_query";
    $countParams[':search_query'] = '%' . $searchQuery . '%';
}

$countStmt = execute($countSql, $countParams);
$totalCount = $countStmt->fetchColumn();
$totalPages = ceil($totalCount / 8);


$sql = "
    SELECT
        per.*, 
        proj.project_name
    FROM Personnels per
    LEFT JOIN Projects proj ON per.project_id = proj.project_id
";

$params = [];

if (!empty($searchQuery)) {
    $sql .= " WHERE per.name LIKE :search_query
              OR per.position LIKE :search_query
              OR proj.project_name LIKE :search_query";
    $params[':search_query'] = '%' . $searchQuery . '%';
}

$sql .= " ORDER BY per.name ASC"; 

$offset = 8 * ($p - 1);
$sql .= " LIMIT 8 OFFSET " . $offset;

$stmt = execute($sql, $params);
$personnel = $stmt->fetchAll();

$startIndex = $offset + 1;
$endIndex = min($offset + count($personnel), $totalCount);

$stmt = execute('SELECT project_name FROM Projects WHERE deleted = 0');
$projects = $stmt->fetchAll();
$options = array_map(function ($item) {
    return $item['project_name'];
}, $projects);

echo $twig->render('personnels.twig', [
    'options' => $options,
    'personnel' => $personnel,
    'count' => count($personnel),
    'current_page' => $p,
    'total_pages' => $totalPages,
    'search_query' => $searchQuery,
    'total_count' => $totalCount,
    'start_index' => $startIndex,
    'end_index' => $endIndex
]);
?>
