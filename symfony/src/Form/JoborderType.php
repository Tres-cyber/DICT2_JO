<?php

namespace App\Form;

use App\Entity\JobOrder;
use App\Service\FormUtils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JoborderType extends AbstractType
{
  public function __construct(private FormUtils $formUtils) {}

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
      $jobOrder = $event->getData();
      $form = $event->getForm();

      $submitted = $jobOrder->getStatus() == 'APPROVED'
        || $jobOrder->getStatus() == 'COMPLETED';

      $form
        /* client info */
        ->add('client_name', null, ['disabled' => $submitted])
        ->add('client_lgu', null, ['disabled' => $submitted])
        ->add('client_contact', null, ['disabled' => $submitted])
        ->add('request_date', null, [
          'widget' => 'single_text',
          'disabled' => $submitted,
        ])

        /* joborder details */
        ->add('scheduled_start_date', null, [
          'widget' => 'single_text',

          'disabled' => $submitted,
        ])
        ->add('scheduled_end_date', null, [
          'widget' => 'single_text',
          'disabled' => $submitted,
        ])
        ->add('issuer', PersonnelAutocompleteField::class, ['disabled' => $submitted])
        ->add('approver', PersonnelAutocompleteField::class, ['disabled' => $submitted])
        ->add('endorsee', PersonnelAutocompleteField::class, [
          'multiple' => true,
          'disabled' => $submitted,
        ])
        ->add('job_description', null, ['disabled' => $submitted])

        /* completion info */
        ->add('start_time', null, [
          'widget' => 'single_text',
        ])
        ->add('end_time', null, [
          'widget' => 'single_text',
        ])
        ->add('actual_job_done')
        ->add('remarks')
        ->add('verifier_name')
        ->add('verifier_position');

      $form->add('draft', SubmitType::class, [
        'label' => 'Save as draft',
        'attr' => ['class' => 'btn-secondary'],
      ]);

      if ($jobOrder->getStatus() == 'DRAFT') {
        $form->add('submit', SubmitType::class, [
          'label' => 'Submit',
          'attr' => ['class' => 'btn-primary'],
        ]);
      }
      if ($jobOrder->getStatus() == 'APPROVED') {
        $form->add('complete', SubmitType::class, [
          'label' => 'Mark completed',
          'attr' => ['class' => 'btn-primary'],
        ]);
      }
    });
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => JobOrder::class,
      'validation_groups' => function (FormInterface $form) {
        $this->formUtils->setForm($form);

        if ($this->formUtils->isClicked('submit')) {
          return ['Default', 'submitted'];
        }
        if ($this->formUtils->isClicked('complete')) {
          return ['Default', 'submitted', 'completed'];
        }

        return ['Default'];
      },
    ]);
  }
}
