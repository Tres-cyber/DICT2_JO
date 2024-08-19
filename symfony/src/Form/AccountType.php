<?php

namespace App\Form;

use App\Entity\Account;
use App\Repository\PersonnelRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
  private FlashBagInterface $flashes;

  public function __construct(RequestStack $requestStack)
  {
    /** @var \Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface */
    $session = $requestStack->getCurrentRequest()->getSession();
    $this->flashes = $session->getFlashBag();
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('personnel', PersonnelAutocompleteField::class, [
        'query_builder' => function (PersonnelRepository $repository) {
          return $repository->createJoinedQueryBuilder()
            ->where('account IS NULL');
        },
      ])
      ->add('email')
      ->add('password', PasswordType::class)
      ->add('confirmPassword', PasswordType::class, [
        'mapped' => false,
      ])
      ->add('is_admin')
      ->add('save', SubmitType::class, [
        'label' => 'Save',
      ]);

    $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
      $data = $event->getData();
      $form = $event->getForm();

      $confirmPassword = $form->get('confirmPassword')->getData();
      if ($data->getPassword() !== $confirmPassword) {
        $form->get('confirmPassword')->addError(new FormError('Passwords must match.'));
        $form->get('password')->addError(new FormError('Passwords must match.'));
        $this->flashes->add('notifications', [
          'title' => 'Account creation failed',
          'message' => "Password doesn't match",
        ]);
      }
    });
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Account::class,
    ]);
  }
}
