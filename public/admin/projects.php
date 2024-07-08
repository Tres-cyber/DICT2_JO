<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $sql = "
    INSERT INTO Projects (
        focal_person_id, project_name ,project_code,project_logo
    ) VALUES (
        :focal_person_id, :project_name, :project_code, :project_logo
    );";
    
    $id = execute("SELECT personnel_id FROM Personnels WHERE name = :n", ['n' => $_POST['focal_person']])->fetchColumn();
    if (!$id) $id = null;

    $args = [
        ':focal_person_id' => $id,
        ':project_name' => $_POST['project_name'],
        ':project_code' => $_POST['project_code'],
        ':project_logo' => $_POST['project_logo']
    ];
    
    execute($sql,$args);
    
    header('Location: /admin/projects.php');
    exit();
    
    }

    $sql = "
    SELECT
        *,
        pa.name
    FROM Projects proj
    JOIN Personnels pa ON proj.focal_person_id = pa.personnel_id
    ";
$stmt = execute($sql);
$project = $stmt->fetchAll();

$stmt = execute('SELECT name FROM Personnels');
$personnels = $stmt->fetchAll();
$options = array_map(function ($item) {
  return $item['name'];
}, $personnels);





echo $twig->render('projects.twig', ['options'=> $options,'project' => $project, 'count' => count($project)]);

