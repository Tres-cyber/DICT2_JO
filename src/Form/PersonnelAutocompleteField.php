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
        return $repository->createJoinedQueryBuilder()
          ->addSelect('CASE WHEN project.id IS NULL THEN 1 ELSE 0 END AS HIDDEN null_order')
          ->addOrderBy('null_order', 'ASC')
          ->addOrderBy('project.name', 'ASC');
      },
      'group_by' =>
      function (Personnel $choice, $key, $value) {
        $project = $choice->getProject();
        if (is_null($project)) return 'Not assigned';
        return $project->getName();
      },
    ]);
  }

  public function getParent(): string
  {
    return BaseEntityAutocompleteType::class;
  }
}
