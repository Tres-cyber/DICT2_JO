<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/vite.php';
?>
<!DOCTYPE html>
<html lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="icon" href="/favicon.ico">
  <title>DICT JO</title>
  <?= vite('main.ts') ?>
</head>

<body>
  <div id="app" class="bg-red-50 mx-0">
    <hello-world></hello-world>
  </div>
</body>

</html>
