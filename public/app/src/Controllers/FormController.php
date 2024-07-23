<?php

namespace App\Controllers;

use App\Form\Type\JoborderType;
use DateTime;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class FormController extends BaseController
{
  private function fetchJO($id)
  {
    $sql = "
        SELECT
            *,
            pi.name AS issued_by,
            pa.name AS approved_by,
            pp.name AS performed_by,
            pp.position AS performer_position,
            pr.project_logo
        FROM JobOrder jo
        LEFT JOIN Personnels pi ON jo.issued_by = pi.personnel_id
        LEFT JOIN Personnels pa ON jo.approved_by = pa.personnel_id
        LEFT JOIN Personnels pp ON jo.performer_id = pp.personnel_id
        LEFT JOIN Projects pr ON jo.project_id = pr.project_id
        WHERE jo.job_order_id = :id
";
    $stmt = execute($sql, [':id' => $id]);

    $jo =  $stmt->fetch();
    if (!$jo) return false;

    $stmt = execute("SELECT p.name 
        FROM EndorsedPersonnels ep
        JOIN Personnels p ON ep.personnel_id = p.personnel_id
        WHERE ep.job_order_id = ?
        ORDER BY ep.personnel_id = ? DESC
    ", [$id, $jo['performer_id']]);
    $endorsee = $stmt->fetchAll();
    $names = array_map(function ($item) {
      return $item['name'];
    }, $endorsee);

    $jo['endorsee'] = $names;
    return $jo;
  }

  public function show(Request $request)
  {
    $account = protectRoute();

    $stmt = execute('SELECT name FROM Personnels');
    $personnels = $stmt->fetchAll();
    $options = array_map(function ($item) {
      return $item['name'];
    }, $personnels);

    $id = $request->query->get('id');
    $jo = $this->fetchJO($id);

    if (!$jo) {
      return $this->render('404.twig');
    }
    if ($jo['performer_id'] !== $account['personnel_id'] and !$account['admin']) {
      return new RedirectResponse('/dashboard.php');
    }

    $simulateDone = \App\Util\Env::isDev() && $request->query->has('done');

    $currentDate = new DateTime();
    $endDate = new DateTime($jo['scheduled_end_date']);
    $due = $endDate < $currentDate;

    $form = $this->createForm(JoborderType::class);

    return $this->render('form.twig', [
      'admin' => $account['admin'],
      'form' => $form->createView(),
      'jo' => $jo,
      'endorsee' => $jo['endorsee'],
      'options' => $options,
      'name' => $jo['performed_by'],
      'done' => $jo['status'] == 'Approved' && ($simulateDone || $due),
      'id' => $id,
    ]);
  }

  private function insert($jo)
  {
    $account = protectRoute();
    $sql = "
INSERT INTO JobOrder (
    job_order_id, project_id, scheduled_start_date, scheduled_end_date, performer_id, 
    job_description, start_time, end_time, actual_job_done, remarks,
    client_name, verifier_position, client_contact, client_lgu, request_mode,
    request_date, issued_by, approved_by, job_order_number, status, verifier
) VALUES (
    :job_order_id, :project_id, :scheduled_start_date, :scheduled_end_date, :performer_id, :job_description, 
    CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, :actual_job_done, :remarks, :client_name, :verifier_position,
    :client_contact, :client_lgu, 'On Site', :request_date, :issued_by,
    :approved_by, :job_order_number, :status, :verifier
);";

    $issued_by = execute("SELECT personnel_id FROM Personnels WHERE name = :n", ['n' => $jo['issued_by']])->fetchColumn();
    if (!$issued_by) $issued_by = null;

    $stmt = execute("SELECT name, position, project_id FROM Personnels WHERE personnel_id = :id", [':id' => $account['personnel_id']]);
    $personnel = $stmt->fetch();

    $approved_by = execute("SELECT personnel_id FROM Personnels WHERE name = :n", ['n' => $jo['approved_by']])->fetchColumn();
    if (!$approved_by) $approved_by = null;


    $approved_by = execute("SELECT personnel_id FROM Personnels WHERE name = :n", ['n' => $jo['approved_by']])->fetchColumn();
    if (!$approved_by) $approved_by = null;

    $args = [
      ':job_order_id' => NULL,
      ':project_id' => $personnel['project_id'],
      ':scheduled_start_date' => $jo['start_scheduled']->format('Y-m-d'),
      ':scheduled_end_date' => $jo['end_scheduled']->format('Y-m-d'),
      ':performer_id' => (int)$account['personnel_id'],
      ':job_description' => $jo['job_description'],
      ':actual_job_done' => $jo['actual_job_done'],
      ':remarks' =>   $jo['remarks'],
      ':client_name' => $jo['client_name'],
      ':client_contact' => $jo['client_contact'],
      ':client_lgu' => $jo['client_lgu'],
      ':request_date' => $jo['request_date']->format('Y-m-d'),
      ':verifier' => $jo['verifier'],
      ':verifier_position' => $jo['verifier_position'],
      ':job_order_number' => $jo['job_order_number'],
      ':issued_by' => $issued_by,
      ':approved_by' => $approved_by,
      ':status' => $jo['status'],
    ];

    execute($sql, $args);
    $id = getDB()->lastInsertId();

    $endorsee = array_unique(array_merge($jo['endorsee'], [$personnel['name']]));

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
  }

  private function update($jo)
  {
    $account = protectRoute();
    $stmt = execute("SELECT name, position, project_id FROM Personnels WHERE personnel_id = :id", [':id' => $account['personnel_id']]);
    $personnel = $stmt->fetch();

    $sql = "
UPDATE JobOrder SET
    project_id = :project_id,
    scheduled_start_date = :scheduled_start_date,
    scheduled_end_date = :scheduled_end_date,
    performer_id = :performer_id,
    job_description = :job_description,
    start_time = CURRENT_TIMESTAMP,
    end_time = CURRENT_TIMESTAMP,
    actual_job_done = :actual_job_done,
    remarks = :remarks,
    client_name = :client_name,
    verifier_position = :verifier_position,
    client_contact = :client_contact,
    client_lgu = :client_lgu,
    request_mode = 'On Site',
    request_date = :request_date,
    issued_by = :issued_by,
    approved_by = :approved_by,
    job_order_number = :job_order_number,
    status = :status,
    verifier = :verifier
WHERE
    job_order_id = :job_order_id;
);";

    $issued_by = execute("SELECT personnel_id FROM Personnels WHERE name = :n", ['n' => $jo['issued_by']])->fetchColumn();
    if (!$issued_by) $issued_by = null;

    $approved_by = execute("SELECT personnel_id FROM Personnels WHERE name = :n", ['n' => $jo['approved_by']])->fetchColumn();
    if (!$approved_by) $approved_by = null;

    $args = [
      ':project_id' => $personnel['project_id'],
      ':job_order_id' => $jo['id'],
      ':scheduled_start_date' => $jo['start_scheduled']->format('Y-m-d'),
      ':scheduled_end_date' => $jo['end_scheduled']->format('Y-m-d'),
      ':performer_id' => (int)$account['personnel_id'],
      ':job_description' => $jo['job_description'],
      ':actual_job_done' => $jo['actual_job_done'],
      ':remarks' =>   $jo['remarks'],
      ':client_name' => $jo['client_name'],
      ':client_contact' => $jo['client_contact'],
      ':client_lgu' => $jo['client_lgu'],
      ':request_date' => $jo['request_date']->format('Y-m-d'),
      ':verifier' => $jo['verifier'],
      ':verifier_position' => $jo['verifier_position'],
      ':job_order_number' => $jo['job_order_number'],
      ':issued_by' => $issued_by,
      ':approved_by' => $approved_by,
      ':status' => $jo['status'],
    ];

    execute($sql, $args);

    execute("DELETE FROM EndorsedPersonnels WHERE job_order_id = :id", ['id' => $jo['id']]);

    $endorsee = array_unique(array_merge($jo['endorsee'], [$personnel['name']]));

    $sql = "INSERT INTO EndorsedPersonnels (job_order_id, personnel_id) VALUES (:job_order_id, :personnel_id)";
    $stmt = getDB()->prepare($sql);

    $personnelStmt = getDB()->prepare("SELECT personnel_id FROM Personnels WHERE name = :personnelName");

    foreach ($endorsee as $name) {
      $personnelStmt->execute([':personnelName' => $name]);
      $personnelId = $personnelStmt->fetchColumn();

      $stmt->execute([
        ':job_order_id' => $jo['id'],
        ':personnel_id' => $personnelId,
      ]);
    }
  }

  public function create(Request $request)
  {
    $account = protectRoute();
    $form = $this->createForm(JoborderType::class);

    $form->handleRequest($request);
    if (!$form->isSubmitted()) {
      return new Response("Bad request", 400);
    }

    $endorsee = isset($_POST['joborder']['endorsee']) ? $_POST['joborder']['endorsee'] : [];

    $stmt = execute("SELECT name, position, project_id FROM Personnels WHERE personnel_id = :id", [':id' => $account['personnel_id']]);
    $personnel = $stmt->fetch();

    $jo = $form->getData();
    $jo['endorsee'] = $endorsee;
    $jo['status'] = $form->get('draft')->isClicked() ? 'Draft' : 'Approved';
    $jo['job_order_number'] = $form->get('draft')->isClicked() ?
      null :
      generateJobOrderId(getDB(), $personnel['project_id'], $jo['request_date']->format('Y-m-d'));


    /** @var Session $session */
    $session = $request->getSession();
    $flashes = $session->getFlashBag();

    if ($form->get('submit')->isClicked()) {
      if (!$form->isValid()) {
        $errors = [];

        foreach ($form->getErrors(true, true) as $v) {
          $origin = $v->getOrigin()->getName();
          $errors[] = [
            "origin" => $origin,
            "message" => $v->getMessage()
          ];
        }
        $flashes->set('jo', $form->getData());
        $flashes->set('form_errors', $errors);
        return new RedirectResponse($request->getRequestUri());
      }
    }

    if (is_null($jo['id'])) {
      $this->insert($jo);
    } else {
      $this->update($jo);
    }

    return new RedirectResponse('/dashboard.php');
  }

  public function new(Request $request)
  {
    $account = protectRoute();

    $stmt = execute("SELECT name, position, project_id FROM Personnels WHERE personnel_id = :id", [':id' => $account['personnel_id']]);
    $personnel = $stmt->fetch();

    $stmt = execute('SELECT name FROM Personnels');
    $personnels = $stmt->fetchAll();
    $jo_num = generateJobOrderId(getDB(), $personnel['project_id'], '2000-01-01', true);

    $options = array_map(function ($item) {
      return $item['name'];
    }, $personnels);


    $jo = null;

    /** @var Session $session */
    $session = $request->getSession();
    $flashes = $session->getFlashBag();

    $jo = [
      'status' => 'Draft',
      'job_order_number' => $jo_num,
      'performed_by' => $personnel['name'],
      'performer_position' => $personnel['position'],
      'endorsee' => [$personnel['name']],
    ];

    $id  = $request->query->get('edit');
    if (!is_null($id)) {
      $jo = $this->fetchJO($id);
      $jo['id'] = $id;
    }

    if ($flashes->has('jo')) {
      $jo = array_merge($jo, $flashes->get('jo'));
    }

    $form = $this->createForm(JoborderType::class, null, ['data' => $jo]);

    /** @var Session $session */
    $session = $request->getSession();
    $flashes = $session->getFlashBag();

    if ($flashes->has('form_errors')) {
      $errors = $flashes->get('form_errors');

      foreach ($errors as $e) {
        $form->get($e['origin'])->addError(new FormError($e['message']));
      }
    }

    return $this->render('create_jo.twig', [
      'form' => $form->createView(),
      'options' => $options,
    ]);
  }

  public function delete(Request $request)
  {
    $account = protectRoute();

    $jo = $this->fetchJO($request->query->get('delete'));

    if ($jo['performer_id'] === $account['personnel_id']) {
      execute("DELETE FROM JobOrder WHERE job_order_id = :id", ['id' => $request->query->get('delete')]);
    }

    header('Location: /dashboard.php');
    exit();
  }
}
