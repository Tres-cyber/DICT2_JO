<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/vite.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="favicon.ico" />
  <title>DICT JO</title>
  <?= vite('main.ts') ?>
</head>

<body>
  <div id="app">
    <div class="container mx-auto flex justify-center print:!m-0 print:!max-w-full">
      <div class="h-[297mm] w-[210mm] border border-neutral-700 p-[0.75in] print:!w-full">
        <div class="relative flex h-[0.75in] items-center justify-between">
          <img src="./bagon_pilipinas.png" alt="Bagong Pilipinas Logo" class="h-[0.5in]" />
          <img src="dict_header.png" alt="DICT Official Header" class="absolute right-1/2 h-[0.75in] translate-x-1/2" />
          <img src="elgu.png" alt="eLGU IBPLS logo" class="h-[0.5in]" />
        </div>
        <div class="flex justify-center">
          <h1 class="mt-4 font-serif text-3xl font-semibold uppercase">
            job order form
          </h1>
        </div>
        <table class="w-full border border-black font-serif">
          <tr>
            <th class="bg-slate-100 text-xs font-semibold uppercase leading-none" colspan="2">
              Client/Worksite Details
            </th>
          </tr>
          <tr>
            <td></td>
            <td></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</body>

</html>
