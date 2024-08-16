<?php

namespace App\Form;

use App\Entity\Personnel;
use App\Repository\PersonnelRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class PersonnelAutocompleteField extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'class' => Personnel::class,
      'choice_label' => 'name',
      'searchable_fields' => ['name'],
      'security' => 'ROLE_USER',
      'query_builder' => function (PersonnelRepository $repository) {
        return $repository->createJoinedQueryBuilder();
      },
    ]);
  }

  public function getParent(): string
  {
    return BaseEntityAutocompleteType::class;
  }
}
