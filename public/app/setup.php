<?php
require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views');
$twig = new \Twig\Environment($loader, [
  'autoescape' => 'html',
]);

$twig->addFunction(new \Twig\TwigFunction('vite', [\Dict\Jo\ViteUtil::class, 'vite']));
