<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-test-user',
    description: 'Crée un utilisateur test pour JWT login'
)]
class CreateTestUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = 'kenway';
        $password = 'azertyuiop';

        $user = new User();
        $user->setEmail($email)
             ->setUsername('kenway')
             ->setIsActive(true)
             ->setCredits(0)
             ->setCreatedAt(new \DateTimeImmutable())
             ->setUpdatedAt(new \DateTimeImmutable());

        $hashed = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashed);

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln("Utilisateur '$email' créé avec succès.");

        return Command::SUCCESS;
    }
}
