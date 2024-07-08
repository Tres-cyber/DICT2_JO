<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateRangeType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('start', DateType::class, [
        'widget' => 'single_text',
        'label' => 'Start Date',
      ])
      ->add('end', DateType::class, [
        'widget' => 'single_text',
        'label' => 'End Date',
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'label_attr' => ['class' => 'hidden'],
      'constraints' => [
        new class extends ConstraintValidator
        {
          public function validate($value, Constraint $constraint)
          {
            if (!$value['start'] || !$value['end']) {
              return;
            }

            $start = $value['start'];
            $end = $value['end'];

            if ($start > $end) {
              $this->context->buildViolation('Start date must not be later than end date')
                ->addViolation();
            }
          }
        }
      ],
    ]);
  }
}
