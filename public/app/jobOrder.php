<?php

function generateJobOrderId($pdo, $projectId, $requestDate, $dummy = false)
{
  // Fetch the project code using the project_id
  $projectCodeSql = "SELECT project_code FROM Projects WHERE project_id = :projectId";
  $stmt = $pdo->prepare($projectCodeSql);
  $stmt->execute([':projectId' => $projectId]);
  $projectCode = $stmt->fetchColumn();

  if (!$projectCode) {
    throw new Exception("Invalid project_id: $projectId");
  }

  // Parse the request date
  $date = new DateTime($requestDate);
  $yearMonth = $date->format('Y-m'); // Format date as YYYY-MM

  // SQL to count existing job orders for the given project, year, and month
  $countSql = "
        SELECT COUNT(*) 
        FROM JobOrder 
        WHERE 
            project_id = :projectId
            AND DATE_FORMAT(request_date, '%Y-%m') = :yearMonth
            AND status != 'Draft'
    ";

  // Prepare and execute the query
  $stmt = $pdo->prepare($countSql);
  $stmt->execute([
    ':projectId' => $projectId,
    ':yearMonth' => $yearMonth,
  ]);

  // Fetch the count
  $count = $stmt->fetchColumn();
  $newSequence = $count + 1; // Increment count for the new sequence

  // Format the new job order ID
  $formattedDate = $date->format('Y-m-d');
  if (!$dummy) {
    $jobOrderId = sprintf("%s-%s-%s-S%02d", $projectCode, 'R2', $formattedDate, $newSequence);
  } else {
    $jobOrderId = sprintf("%s-%s-%s-S%02d", $projectCode, 'R2', 'YYYY-MM-DD', 'S00');
  }

  return $jobOrderId;
}
