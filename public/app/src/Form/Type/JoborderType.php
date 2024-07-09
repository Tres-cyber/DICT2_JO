<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;

class JoborderType extends AbstractType
{
  function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('id', HiddenType::class)
      ->add('client_name', TextType::class, ['constraints' => [new NotBlank()]])
      ->add('client_contact', TelType::class, ['constraints' => [new NotBlank()]])
      ->add('client_lgu', TextType::class, ['constraints' => [new NotBlank()]])
      ->add('request_date', DateType::class, ['constraints' => [new NotBlank(), new Date()]])
      ->add('start_scheduled', DateType::class, ['constraints' => [new NotBlank(), new Date()]])
      ->add('end_scheduled', DateType::class, ['constraints' => [new NotBlank(), new Date()]])
      ->add('issued_by', TextType::class, ['constraints' => [new NotBlank()]])
      ->add('approved_by', TextType::class, ['constraints' => [new NotBlank()]])
      ->add('endorsee', CollectionType::class, ['entry_type' => TextType::class, 'constraints' => [new NotBlank()]])
      ->add('job_description', TextareaType::class, ['constraints' => [new NotBlank()]])
      ->add('actual_job_done', TextareaType::class, ['constraints' => [new NotBlank()]])
      ->add('verifier', TextType::class, ['constraints' => [new NotBlank()]])
      ->add('verifier_position', TextType::class, ['constraints' => [new NotBlank()]])
      ->add('remarks', TextareaType::class, ['constraints' => [new NotBlank()]])
      ->add('draft', SubmitType::class, ['label' => 'Draft'])
      ->add('submit', SubmitType::class, ['label' => 'Submit']);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => null,
      'attr' => [
        'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!  🚥
      ]
    ]);
  }
}
