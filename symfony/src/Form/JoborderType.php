<?php

namespace App\Form;

use App\Entity\JobOrder;
use App\Entity\Personnel;
use App\Repository\PersonnelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JoborderType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
      $jobOrder = $event->getData();
      $form = $event->getForm();

      $form
        ->add('scheduled_start_date', null, [
          'widget' => 'single_text',
        ])
        ->add('scheduled_end_date', null, [
          'widget' => 'single_text',
        ])
        ->add('job_description')
        ->add('start_time', null, [
          'widget' => 'single_text',
        ])
        ->add('end_time', null, [
          'widget' => 'single_text',
        ])
        ->add('actual_job_done')
        ->add('remarks')
        ->add('client_name')
        ->add('client_contact')
        ->add('client_lgu')
        ->add('request_date', null, [
          'widget' => 'single_text',
        ])
        ->add('verifier_name')
        ->add('verifier_position')
        ->add('issuer', PersonnelAutocompleteField::class)
        ->add('approver', PersonnelAutocompleteField::class)
        ->add('endorsee', PersonnelAutocompleteField::class, ['multiple' => true]);

      dump($jobOrder);

      $form->add('draft', SubmitType::class, [
        'label' => 'Draft',
        'attr' => ['class' => 'btn-secondary'],
      ])->add('submit', SubmitType::class, [
        'label' => 'Submit',
        'attr' => ['class' => 'btn-primary'],
      ]);
    });
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => JobOrder::class,
      'validation_groups' => function (FormInterface $form) {
        if ($form->get('submit')->isClicked()) {
          return ['Default', 'submitted'];
        }
        return ['Default'];
      },
    ]);
  }
}
