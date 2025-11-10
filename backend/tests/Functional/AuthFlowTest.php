<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;

class AuthFlowTest extends WebTestCase
{
    public function testLoginAndRefreshTokenFlow(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();

        // --- 1️⃣ Créer un utilisateur test dans la BDD test
        $em = $container->get('doctrine')->getManager();

        $user = new User();
        $user->setEmail('sameme@example.com');
        $user->setPassword(password_hash('azertyuiop974', PASSWORD_BCRYPT)); // hasher comme en prod
        $user->setRoles(['ROLE_USER']);
        $user->setUsername('TestUser');
        $user->setIsActive(true);
        $user->setCredits(0);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($user);
        $em->flush();

        // --- 2️⃣ Login
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'sameme@example.com',
                'password' => 'azertyuiop974',
            ])
        );

        $this->assertResponseIsSuccessful('Login devrait réussir');
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data, 'JWT doit être retourné');
        $jwt = $data['token'];

        // --- 3️⃣ Vérifier que le cookie REFRESH_TOKEN est créé
        $refreshCookie = $client->getCookieJar()->get('REFRESH_TOKEN');
        $this->assertNotNull($refreshCookie, 'Le cookie REFRESH_TOKEN doit exister');
        $this->assertTrue($refreshCookie->isHttpOnly(), 'Le cookie doit être HttpOnly');
        $this->assertEquals('/api/token/refresh', $refreshCookie->getPath(), 'Le path du cookie doit être correct');

        // --- 4️⃣ Simuler un refresh token
        $client->getCookieJar()->set($refreshCookie); // ajouter le cookie au client
        $client->request(
            'POST',
            '/api/token/refresh',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseIsSuccessful('Refresh token doit réussir');
        $refreshData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $refreshData, 'Un nouveau JWT doit être retourné');
    }
}
