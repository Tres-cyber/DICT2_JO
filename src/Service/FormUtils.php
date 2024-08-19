<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;

class FormUtils
{
  private ?FormInterface $form = null;

  public function setForm(FormInterface $form): static
  {
    $this->form = $form;

    return $this;
  }

  public function getForm(): ?FormInterface
  {
    return $this->form;
  }

  public function isClicked(string $name, ?FormInterface $form = null): bool
  {
    if (is_null($form)) {
      $form = $this->form;
    }

    if (!$form->has($name)) return false;
    /** @var \Symfony\Component\Form\SubmitButton */

    $button = $form->get($name);
    return $button->isClicked();
  }
}
