<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

class FilenameTransformer implements DataTransformerInterface
{
  private string $targetDirectory;
  private string $baseDirectory;

  public function __construct(KernelInterface $kernel)
  {
    $this->targetDirectory = $this->baseDirectory = $kernel->getProjectDir() . '/public';
  }

  public function setTargetDirectory(string $directory): void
  {
    $this->targetDirectory = $directory;
  }

  public function transform(mixed $value): ?File
  {
    if (is_null($value) || !is_string($value)) {
      return null;
    }

    return new File($this->baseDirectory . $value);
  }


  public function reverseTransform($value): ?string
  {
    if (null === $value) {
      return null;
    }

    if (!$value instanceof UploadedFile) {
      return $value;
    }

    $fileName = uniqid() . '.' . $value->guessExtension();
    $logoDirectory = $this->baseDirectory . $this->targetDirectory;

    try {
      $value->move($logoDirectory, $fileName);
    } catch (FileException $e) {
      throw new TransformationFailedException('Error uploading file: ' . $e->getMessage());
    }

    return $this->targetDirectory . '/' . $fileName;
  }
}
