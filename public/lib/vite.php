<?php
define("VITE_HOST", $_ENV["VITE_HOST"]);

function vite(string $entry): string
{
  return "\n" . jsTag($entry)
    . "\n" . jsPreloadImports($entry)
    . "\n" . cssTag($entry);
}

function isDev(): bool
{
  return isset($_ENV['MODE_DEPLOY']) && $_ENV['MODE_DEPLOY'] == 'development';
}

function jsTag(string $entry): string
{
  $url = isDev()
    ? VITE_HOST . '/' . $entry
    : assetUrl($entry);

  if (!$url) {
    return '';
  }
  if (isDev()) {
    return '<script type="module" src="' . VITE_HOST . '/@vite/client"></script>' . "\n"
      . '<script type="module" src="' . $url . '"></script>';
  }
  return '<script type="module" src="' . $url . '"></script>';
}

function jsPreloadImports(string $entry): string
{
  if (isDev()) {
    return '';
  }

  $res = '';
  foreach (importsUrls($entry) as $url) {
    $res .= '<link rel="modulepreload" href="'
      . $url
      . '">';
  }
  return $res;
}

function cssTag(string $entry): string
{
  // not needed on dev, it's inject by Vite
  if (isDev()) {
    return '';
  }

  $tags = '';
  foreach (cssUrls($entry) as $url) {
    $tags .= '<link rel="stylesheet" href="'
      . $url
      . '">';
  }
  return $tags;
}

function getManifest(): array
{
  $content = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/dist/.vite/manifest.json');
  return json_decode($content, true);
}

function assetUrl(string $entry): string
{
  $manifest = getManifest();

  return isset($manifest[$entry])
    ? '/dist/' . $manifest[$entry]['file']
    : '';
}

function importsUrls(string $entry): array
{
  $urls = [];
  $manifest = getManifest();

  if (!empty($manifest[$entry]['imports'])) {
    foreach ($manifest[$entry]['imports'] as $imports) {
      $urls[] = '/dist/' . $manifest[$imports]['file'];
    }
  }
  return $urls;
}

function cssUrls(string $entry): array
{
  $urls = [];
  $manifest = getManifest();

  if (!empty($manifest[$entry]['css'])) {
    foreach ($manifest[$entry]['css'] as $file) {
      $urls[] = '/dist/' . $file;
    }
  }
  return $urls;
}
