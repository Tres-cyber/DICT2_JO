<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminAccountFixtures extends Fixture
{
  public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

  public function load(ObjectManager $manager): void
  {
    $admin = new Account();
    $admin->setAdmin(true);
    $admin->setEmail('admin@dict.gov.ph');
    $admin->setDeleted(false);
    $password = $this->passwordHasher->hashPassword($admin, 'adminpassword');
    $admin->setPassword($password);
    $manager->persist($admin);

    $manager->flush();
  }
}
