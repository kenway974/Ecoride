<?php

namespace App\Tests\Functional;

use App\Entity\RefreshToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\Tools\SchemaTool;

class AuthFlowTest extends WebTestCase
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();

        $this->em = $container->get(EntityManagerInterface::class);
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);

        $this->resetDatabase();
        $this->createUser('john@example.com', 'password123');
        $this->createRefreshToken();
    }

    private function resetDatabase(): void
    {
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        if (!empty($metadata)) {
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        }
    }

    private function createUser(string $email, string $password): void
    {
        $user = new User();
        $user->setEmail($email)
            ->setUsername('john')
            ->setPassword($this->passwordHasher->hashPassword($user, $password))
            ->setCredits(20)
            ->setIsActive(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

        $user->setRoles(['ROLE_USER']);

        $this->em->persist($user);
        $this->em->flush();
    }

    private function createRefreshToken(): void
    {
        $refreshToken = new RefreshToken();
        $refreshToken->setRefreshToken(bin2hex(random_bytes(32)));
        $refreshToken->setUsername('john@example.com');
        $refreshToken->setValid((new \DateTimeImmutable())->modify('+30 days'));

        $this->em->persist($refreshToken);
        $this->em->flush();
    }


    public function testLoginSuccess(): void
    {
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'john@example.com',
                'password' => 'password123'
            ])
        );

        $this->assertResponseStatusCodeSame(200);
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('token', $data, 'Le token JWT doit être présent.');
    }

    public function testLoginFailureWrongPassword(): void
    {
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'john@example.com',
                'password' => 'wrongpass'
            ])
        );

        $this->assertResponseStatusCodeSame(401);
    }

    public function testLoginFailureUnknownUser(): void
    {
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'nope@example.com',
                'password' => 'irrelevant'
            ])
        );

        $this->assertResponseStatusCodeSame(401);
    }
}
