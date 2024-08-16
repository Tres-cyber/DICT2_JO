<?php

namespace App\Form;

use App\Entity\Personnel;
use App\Entity\Project;
use App\Form\DataTransformer\FilenameTransformer;
use App\Repository\PersonnelRepository;
use RuntimeException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
  public function __construct(private FilenameTransformer $filenameTransformer, ParameterBagInterface $parameters)
  {
    if (!$parameters->has('logos_directory')) {
      throw new RuntimeException('logos_directory is not set');
    }

    $filenameTransformer->setTargetDirectory($parameters->get('logos_directory'));
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name')
      ->add('code')
      ->add('focal_person', EntityType::class, [
        'class' => Personnel::class,
        'choice_label' => 'name',
        'query_builder' => function (PersonnelRepository $repository) {
          return $repository->createJoinedQueryBuilder();
        },
        'autocomplete' => true,
      ])
      ->add('logo', FileType::class, [
        'required' => false,
        'attr' => ['accept' => 'image/jpeg, image/png']
      ])
      ->add('save', SubmitType::class, [
        'label' => 'Save',
        'attr' => ['data-bs-dismiss' => 'modal']
      ]);;

    $builder
      ->get('logo')
      ->addModelTransformer($this->filenameTransformer);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Project::class,
    ]);
  }
}
