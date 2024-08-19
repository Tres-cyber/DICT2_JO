<?php

namespace App\Service;

class Uniqid
{
  public function __construct(private Base32Encoder $base32Encoder) {}

  public function generate(string $prefix = ""): string
  {
    $randomBytes = random_bytes(3);
    $firstPart = $this->base32Encoder->encode($randomBytes);

    $timestamp = time();
    $secondPart = strtoupper(dechex($timestamp));

    return $prefix . "$firstPart-$secondPart";
  }
}
