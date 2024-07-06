<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/jobOrder.php';

session_start();

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views');
$twig = new \Twig\Environment($loader, [
  'debug' => \Dict\Jo\ViteUtil::isDev(),
  'autoescape' => 'html',
]);

$twig->addGlobal('session', $_SESSION);
if (\Dict\Jo\ViteUtil::isDev()) $twig->addExtension(new \Twig\Extension\DebugExtension());
$twig->addFunction(new \Twig\TwigFunction('vite', [\Dict\Jo\ViteUtil::class, 'vite']));
