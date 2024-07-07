<?php

namespace App\Util;

class Env
{
  public static function isDev(): bool
  {
    return isset($_ENV['MODE_DEPLOY']) && $_ENV['MODE_DEPLOY'] === 'development';
  }
}
