<?php
require_once __DIR__ . '/app/setup.php';
$account = protectRoute();

if ($account['admin']) {
  header('Location: /admin/');
} else {
  header('Location: /dashboard.php');
}
exit();
