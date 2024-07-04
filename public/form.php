<?php
require_once __DIR__ . '/app/setup.php';
$account = protectRoute();

function fetchJO($id)
{
  $sql = "
        SELECT
            *,
            pi.name AS issued_by,
            pa.name AS approved_by,
            pp.name AS performed_by,
            pp.position AS performer_position
        FROM JobOrder jo
        JOIN Personnels pi ON jo.issued_by = pi.personnel_id
        JOIN Personnels pa ON jo.approved_by = pa.personnel_id
        JOIN Personnels pp ON jo.performer_id = pp.personnel_id
        WHERE jo.job_order_id = :id
";
  $stmt = execute($sql, [':id' => $id]);
  return $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if (isset($_GET['delete'])) {
    $jo = fetchJO($_GET['delete']);
    if ($jo['performer_id'] === $account['personnel_id']) {
      execute("DELETE FROM JobOrder WHERE job_order_id = :id", ['id' => $_GET['delete']]);
    }
    header('Location: /dashboard.php');
    exit();
  } else if (isset($_GET['id'])) {
    $jo = fetchJO($_GET['id']);

    if (!$jo) {
      echo $twig->render('404.twig');
    }

    var_dump($jo);
    echo $twig->render('form.twig', ['readonly' => true, 'jo' => $jo]);
  } else {
    echo $twig->render('form.twig', ['readonly' => false]);
  }
}
