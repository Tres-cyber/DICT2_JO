<?php
require_once __DIR__ . '/app/setup.php';
$account = protectRoute();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $jo_num = generateJobOrderId(getDB(), 3, $_POST['request_date']);

  $sql = "
INSERT INTO JobOrder (
    job_order_id, project_id, scheduled_start_date, scheduled_end_date, performer_id, 
    job_description, start_time, end_time, actual_job_done, remarks,
    client_name, verifier_position, client_contact, client_lgu, request_mode,
    request_date, issued_by, approved_by, job_order_number, status, verifier
) VALUES (
    :job_order_id, 3, :scheduled_start_date, :scheduled_end_date, :performer_id, :job_description, 
    CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, :actual_job_done, :remarks, :client_name, :verifier_position,
    :client_contact, :client_lgu, 'On Site', :request_date, :issued_by,
    :approved_by, :job_order_number, :status, :verifier
);";

  $issued_by = execute("SELECT personnel_id FROM Personnels WHERE name = :n", ['n' => $_POST['issued_by']])->fetchColumn();
  if (!$issued_by) $issued_by = null;

  $approved_by = execute("SELECT personnel_id FROM Personnels WHERE name = :n", ['n' => $_POST['approved_by']])->fetchColumn();
  if (!$approved_by) $approved_by = null;

  $args = [
    ':job_order_id' => NULL,
    ':scheduled_start_date' => $_POST['scheduled'][0],
    ':scheduled_end_date' => $_POST['scheduled'][1],
    ':performer_id' => (int)$account['personnel_id'],
    ':job_description' => $_POST['job_description'],
    ':actual_job_done' => $_POST['actual_job_done'],
    ':remarks' => $_POST['remarks'],
    ':client_name' => $_POST['client_name'],
    ':client_contact' => $_POST['client_contact'],
    ':client_lgu' => $_POST['client_lgu'],
    ':request_date' => $_POST['request_date'],
    ':verifier' => $_POST['verifier'],
    ':verifier_position' => $_POST['verifier_position'],
    ':job_order_number' => $jo_num,
    ':issued_by' => $issued_by,
    ':approved_by' => $approved_by,
    ':status' => 'Draft',
  ];

  execute($sql, $args);
  $id = getDB()->lastInsertId();

  $stmt = execute("SELECT name, position FROM Personnels WHERE personnel_id = :id", [':id' => $account['personnel_id']]);
  $personnel = $stmt->fetch();

  $endorsee = array_unique(array_merge($_POST['endorsee'], [$personnel['name']]));

  $sql = "INSERT INTO EndorsedPersonnels (job_order_id, personnel_id) VALUES (:job_order_id, :personnel_id)";
  $stmt = getDB()->prepare($sql);

  $personnelStmt = getDB()->prepare("SELECT personnel_id FROM Personnels WHERE name = :personnelName");

  foreach ($endorsee as $name) {
    $personnelStmt->execute([':personnelName' => $name]);
    $personnelId = $personnelStmt->fetchColumn();

    $stmt->execute([
      ':job_order_id' => $id,
      ':personnel_id' => $personnelId,
    ]);
  }

  header('Location: /dashboard.php');
  exit();
}

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
        LEFT JOIN Personnels pi ON jo.issued_by = pi.personnel_id
        LEFT JOIN Personnels pa ON jo.approved_by = pa.personnel_id
        LEFT JOIN Personnels pp ON jo.performer_id = pp.personnel_id
        WHERE jo.job_order_id = :id
";
  $stmt = execute($sql, [':id' => $id]);
  return $stmt->fetch();
}

if (isset($_GET['delete'])) {
  $jo = fetchJO($_GET['delete']);
  if ($jo['performer_id'] === $account['personnel_id']) {
    execute("DELETE FROM JobOrder WHERE job_order_id = :id", ['id' => $_GET['delete']]);
  }
  header('Location: /dashboard.php');
  exit();
}

$stmt = execute("SELECT name, position FROM Personnels WHERE personnel_id = :id", [':id' => $account['personnel_id']]);
$personnel = $stmt->fetch();

$stmt = execute('SELECT name FROM Personnels');
$personnels = $stmt->fetchAll();
$options = array_map(function ($item) {
  return $item['name'];
}, $personnels);

if (isset($_GET['id'])) {
  $jo = fetchJO($_GET['id']);

  if (!$jo) {
    echo $twig->render('404.twig');
    exit();
  }
  if ($jo['performer_id'] !== $account['personnel_id']) {
    header('Location: /dashboard.php');
    exit();
  }

  $stmt = execute("SELECT p.name 
        FROM EndorsedPersonnels ep
        JOIN Personnels p ON ep.personnel_id = p.personnel_id
        WHERE ep.job_order_id = :id
    ", [':id' => $_GET['id']]);
  $endorsee = $stmt->fetchAll();
  $names = array_map(function ($item) {
    return $item['name'];
  }, $endorsee);

  $simulateDone = \Dict\Jo\ViteUtil::isDev() && isset($_GET['done']);

  $currentDate = new DateTime();
  $endDate = new DateTime($jo['scheduled_end_date']);
  $due = $endDate < $currentDate;

  echo $twig->render('form.twig', [
    'jo' => $jo,
    'endorsee' => $names,
    'options' => $options,
    'name' => $personnel['name'],
    'done' => $jo['status'] == 'Approved' && ($simulateDone || $due),
  ]);
  exit();
}

$jo_num = generateJobOrderId(getDB(), 3, '2000-01-01', true);
$jo = [
  'status' => 'Draft',
  'job_order_number' => $jo_num,
  'performed_by' => $personnel['name'],
  'performer_position' => $personnel['position'],
];

echo $twig->render('form.twig', [
  'jo' => $jo,
  'options' => $options,
  'endorsee' => [$personnel['name']],
  'name' => $personnel['name'],
]);
