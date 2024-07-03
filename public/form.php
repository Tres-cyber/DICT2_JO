<?php
require_once __DIR__ . '/app/setup.php';
protectRoute();

echo $twig->render('form.twig', []);
