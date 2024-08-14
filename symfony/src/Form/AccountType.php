<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Personnel;
use App\Repository\PersonnelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('email')
      ->add('password')
      ->add('is_admin')
      ->add('personnel', EntityType::class, [
        'class' => Personnel::class,
        'choice_label' => 'name',
        'query_builder' => function (PersonnelRepository $repository) {
          return $repository->createJoinedQueryBuilder()
            ->where('account IS NULL');
        }
      ])
      ->add('save', SubmitType::class, [
        'label' => 'Save',
        'attr' => ['data-bs-dismiss' => 'modal']
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Account::class,
    ]);
  }
}
