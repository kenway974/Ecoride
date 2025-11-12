<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class SetUserChauffeurTest extends WebTestCase
{
    private $client;
    private $em;
    private $user;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get(EntityManagerInterface::class);

        // Création d'un utilisateur mock
        $this->user = new User();
        $this->user->setUsername('TestUser');
        $this->user->setEmail('testuser@example.com');
        $this->user->setRoles(['ROLE_CHAUFFEUR']);
        $this->em->persist($this->user);
        $this->em->flush();
    }

    public function testUserBecomesChauffeur(): void
    {
        // === 1️⃣ On set le role à "chauffeur" ===
        $this->client->request(
            'PUT',
            '/api/user/role',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['role' => 'chauffeur'])
        );

        $this->assertResponseIsSuccessful();

        $this->em->refresh($this->user);
        $this->assertEquals(['ROLE_CHAUFFEUR'], $this->user->getRoles());

        // === 2️⃣ On ajoute un véhicule ===
        $vehicleData = [
            'plate' => 'AB-123-CD',
            'brand' => 'Peugeot',
            'model' => '208',
            'release_year' => 2020,
            'energy' => 'essence',
            'seats_total' => 4,
            'seats_available' => 4
        ];

        $this->client->request(
            'POST',
            '/api/user/vehicles',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($vehicleData)
        );

        $this->assertResponseIsSuccessful();
        $this->em->refresh($this->user);
        $this->assertGreaterThanOrEqual(1, count($this->user->getVehicles()));

        // === 3️⃣ On ajoute une préférence ===
        $preferenceData = [
            'animals' => true,
            'smoke' => false,
            'food' => true,
            'is_custom' => false,
            'options' => []
        ];

        $this->client->request(
            'PUT',
            '/api/user/preferences',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($preferenceData)
        );

        $this->assertResponseIsSuccessful();
        $this->em->refresh($this->user);
        $this->assertNotNull($this->user->getPreference());

        $preference = $this->user->getPreference();
        $this->assertTrue($preference->getAnimals());
        $this->assertFalse($preference->getSmoke());
        $this->assertTrue($preference->getFood());
    }

    protected function tearDown(): void
    {
        // nettoyage
        $this->em->remove($this->user);
        $this->em->flush();

        parent::tearDown();
    }
}
