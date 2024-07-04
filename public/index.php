<?php
require_once __DIR__ . '/app/setup.php';
$account = protectRoute();

if ($account['admin']) {
  header('Location: /admin.php');
} else {
  header('Location: /dashboard.php');
}
exit();
