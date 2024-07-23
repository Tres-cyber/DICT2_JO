<?php

namespace App\Form\Type;

use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class JoborderType extends AbstractType
{
  function getChoices()
  {
    static $choice = null;
    if ($choice !== null) return $choice;

    $stmt = execute('SELECT name FROM Personnels');
    $personnels = $stmt->fetchAll();

    $choice = [];
    foreach ($personnels as $p) {
      $choice[$p['name']] = $p['name'];
    }

    return $choice;
  }

  function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('id', HiddenType::class)
      ->add('client_name', TextType::class, ['constraints' => [new Assert\NotBlank()]])
      ->add('client_contact', TelType::class, ['constraints' => [new Assert\NotBlank()]])
      ->add('client_lgu', TextType::class, ['constraints' => [new Assert\NotBlank()]])
      ->add('request_date', DateType::class, ['data' => new DateTime(), 'constraints' => [new Assert\NotBlank(), new Assert\Type("DateTimeInterface")]])
      ->add('start_scheduled', DateType::class, ['data' => new DateTime(), 'constraints' => [new Assert\NotBlank(), new Assert\Type("DateTimeInterface")]])
      ->add('end_scheduled', DateType::class, ['data' => new DateTime(), 'constraints' => [new Assert\NotBlank(), new Assert\Type("DateTimeInterface")]])
      ->add('issued_by', ChoiceType::class, ['choices' => $this->getChoices(), 'constraints' => [new Assert\NotBlank()]])
      ->add('approved_by', ChoiceType::class, ['choices' => $this->getChoices(), 'constraints' => [new Assert\NotBlank()]])
      ->add('endorsee', CollectionType::class, [
        'entry_type' => TextType::class,
        'allow_add' => true,
        'allow_delete' => true,
        'constraints' => [new Assert\NotBlank()]
      ])
      ->add('job_description', TextareaType::class, ['constraints' => [new Assert\NotBlank()]])
      /*
      ->add('actual_job_done', TextareaType::class, ['constraints' => [new Assert\NotBlank()]])
      ->add('verifier', TextType::class, ['constraints' => [new Assert\NotBlank()]])
      ->add('verifier_position', TextType::class, ['constraints' => [new Assert\NotBlank()]])
      ->add('remarks', TextareaType::class, ['constraints' => [new Assert\NotBlank()]])
      */
      ->add('actual_job_done', TextareaType::class, ['constraints' => []])
      ->add('verifier', TextType::class, ['constraints' => []])
      ->add('verifier_position', TextType::class, ['constraints' => []])
      ->add('remarks', TextareaType::class, ['constraints' => []])
      ->add('draft', SubmitType::class, ['label' => 'Draft'])
      ->add('submit', SubmitType::class, ['label' => 'Submit']);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => null,
      'choices' => [],
      'attr' => [
        'novalidate' => 'novalidate',
      ]
    ]);
  }
}
