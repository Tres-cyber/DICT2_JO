<?php

namespace App\Command;

use App\Entity\Account;
use App\Entity\Personnel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
  name: 'app:generate-accounts',
  description: 'Add a short description for your command',
)]
class GenerateAccountsCommand extends Command
{


  public function __construct(
    private EntityManagerInterface $entityManager,
    private UserPasswordHasherInterface $passwordHasher
  ) {
    parent::__construct();
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $personnelRepository = $this->entityManager->getRepository(Personnel::class);
    $allPersonnel = $personnelRepository->findAll();

    // Output CSV header
    $output->writeln('Name,Email,Password');

    foreach ($allPersonnel as $personnel) {
      $email = $this->generateRandomEmail();
      $password = $this->generateRandomPassword();

      $account = new Account();
      $account->setPersonnel($personnel);
      $account->setEmail($email);
      $hashedPassword = $this->passwordHasher->hashPassword($account, $password);
      $account->setPassword($hashedPassword);

      $this->entityManager->persist($account);

      // Output CSV line
      $output->writeln(sprintf(
        '%s,%s,%s',
        $personnel->getName(),
        $email,
        $password
      ));
    }

    $this->entityManager->flush();

    return Command::SUCCESS;
  }

  private function generateRandomEmail(): string
  {
    return sprintf(
      'user+%04d@dict.gov.ph',
      mt_rand(0, 9999)
    );
  }

  private function generateRandomPassword(): string
  {
    return sprintf('%04d', mt_rand(0, 9999));
  }
}
