<?php
require_once __DIR__ . '/app/vendor/autoload.php';


$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/app/views');
$twig = new \Twig\Environment($loader);

$twig->addFunction(new \Twig\TwigFunction('vite', [\Dict\Jo\ViteUtil::class, 'vite']));

echo $twig->render('form.twig', []);
