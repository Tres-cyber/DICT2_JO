<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/jobOrder.php';

session_start();

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views');
$twig = new \Twig\Environment($loader, [
  'debug' => \App\Util\Env::isDev(),
  'autoescape' => 'html',
]);

$twig->addGlobal('session', $_SESSION);
if (\App\Util\Env::isDev()) $twig->addExtension(new \Twig\Extension\DebugExtension());
$twig->addFunction(new \Twig\TwigFunction('vite', [\App\Util\Vite::class, 'vite']));
