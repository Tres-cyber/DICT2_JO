<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;

class JoborderDetailsType extends AbstractType
{
  function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('scheduled_date', DateRangeType::class, ['constraints' => [new NotBlank()]])
      ->add('client_name', TextType::class, ['constraints' => [new NotBlank()]])
      ->add('client_contact', TelType::class, ['constraints' => [new NotBlank()]])
      ->add('client_lgu', TextType::class, ['constraints' => [new NotBlank()]])
      ->add('request_date', DateType::class, ['constraints' => [new NotBlank(), new Date()]])
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'label' => 'Client/Worksite Details',
      'label_attr' => ['class' => 'text-lg font-semibold mb-2 block'],
    ]);
  }
}
