<?php

namespace Dict\Jo;

define("VITE_HOST", $_ENV["VITE_HOST"]);

class ViteUtil
{
  public static function vite(string $entry): string
  {
    return "\n" . self::jsTag($entry)
      . "\n" . self::jsPreloadImports($entry)
      . "\n" . self::cssTag($entry);
  }

  public static function isDev(): bool
  {
    return isset($_ENV['MODE_DEPLOY']) && $_ENV['MODE_DEPLOY'] === 'development';
  }

  private static function jsTag(string $entry): string
  {
    $url = self::isDev()
      ? VITE_HOST . '/' . $entry
      : self::assetUrl($entry);

    if (!$url) {
      return '';
    }
    if (self::isDev()) {
      return '<script type="module" src="' . VITE_HOST . '/@vite/client"></script>' . "\n"
        . '<script type="module" src="' . $url . '"></script>';
    }
    return '<script type="module" src="' . $url . '"></script>';
  }

  private static function jsPreloadImports(string $entry): string
  {
    if (self::isDev()) {
      return '';
    }

    $res = '';
    foreach (self::importsUrls($entry) as $url) {
      $res .= '<link rel="modulepreload" href="' . $url . '">';
    }
    return $res;
  }

  private static function cssTag(string $entry): string
  {
    if (self::isDev()) {
      return '';
    }

    $tags = '';
    foreach (self::cssUrls($entry) as $url) {
      $tags .= '<link rel="stylesheet" href="' . $url . '">';
    }
    return $tags;
  }

  private static function getManifest(): array
  {
    $manifestPath = $_SERVER['DOCUMENT_ROOT'] . '/dist/.vite/manifest.json';
    if (!file_exists($manifestPath)) {
      return [];
    }

    $content = file_get_contents($manifestPath);
    return json_decode($content, true);
  }

  private static function assetUrl(string $entry): string
  {
    $manifest = self::getManifest();

    return isset($manifest[$entry])
      ? '/dist/' . $manifest[$entry]['file']
      : '';
  }

  private static function importsUrls(string $entry): array
  {
    $urls = [];
    $manifest = self::getManifest();

    if (!empty($manifest[$entry]['imports'])) {
      foreach ($manifest[$entry]['imports'] as $imports) {
        $urls[] = '/dist/' . $manifest[$imports]['file'];
      }
    }
    return $urls;
  }

  private static function cssUrls(string $entry): array
  {
    $urls = [];
    $manifest = self::getManifest();

    if (!empty($manifest[$entry]['css'])) {
      foreach ($manifest[$entry]['css'] as $file) {
        $urls[] = '/dist/' . $file;
      }
    }
    return $urls;
  }
}
