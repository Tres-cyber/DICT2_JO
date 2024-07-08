<?php
require_once __DIR__ . '/../app/setup.php';
$account = protectRoute(true);

$sql = "
        SELECT
            *,
            pa.name
        FROM Projects proj
        JOIN Personnels pa ON proj.focal_person_id = pa.personnel_id
";

$stmt = execute($sql);
$project = $stmt->fetchAll();
echo $twig->render('projects.twig', ['project' => $project, 'count' => count($project)]);
