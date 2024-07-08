<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

if (empty($_GET['id'])){
    header('Location: /admin/projects.php');  
    exit();
}

$project_id = $_GET['id'];

$stmt = execute("SELECT project_id FROM Projects WHERE project_id = :project_id", [':project_id' => $project_id]);
$account = $stmt->fetch();


execute("DELETE FROM Projects WHERE project_id = :project_id", [':project_id' => $account['project_id']]);



header('Location: /admin/projects.php');
exit();
