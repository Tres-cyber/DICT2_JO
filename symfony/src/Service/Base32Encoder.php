<?php

namespace App\Service;

use UnexpectedValueException;

class Base32Encoder
{

  private string $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

  public function getAlphabet(): string
  {
    return $this->alphabet;
  }

  public function setAlphabet(string $alphabet): static
  {
    if (strlen($alphabet) < 32) {
      throw new UnexpectedValueException('$alphabet must be atleast 32 charcters long');
    }

    return $this;
  }

  public function encode(mixed $data): string
  {
    $binaryString = '';

    foreach (str_split($data) as $byte) {
      $binaryString .= str_pad(decbin(ord($byte)), 8, '0', STR_PAD_LEFT);
    }

    $base32String = '';
    foreach (str_split($binaryString, 5) as $chunk) {
      $chunk = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
      $base32String .= $this->alphabet[bindec($chunk)];
    }

    return $base32String;
  }
}
