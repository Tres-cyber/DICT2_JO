<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

if (empty($_GET['id'])) {
  header('Location: /admin/projects.php');
  exit();
}

$project_id = (int) $_GET['id'];
execute("DELETE FROM Projects WHERE project_id = :project_id", [':project_id' => $project_id]);

header('Location: /admin/projects.php');
exit();
