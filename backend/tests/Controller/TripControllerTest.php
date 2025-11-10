<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TripControllerTest extends WebTestCase
{
    private const API_BASE = '/api/trips';

    /**
     * Test la récupération de la liste des trips
     */
    public function testIndex(): void
    {
        $client = static::createClient();

        // Requête GET avec paramètres de test
        $client->request('GET', self::API_BASE, [
            'from' => 'Paris',
            'to' => 'Lyon',
        ]);

        $this->assertResponseIsSuccessful(); // 200 OK
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('success', $responseData);
        $this->assertTrue($responseData['success']);

        $this->assertArrayHasKey('data', $responseData);
        $this->assertIsArray($responseData['data']);

        // Vérifie qu'au moins un trip contient le driver et le vehicle
        if (!empty($responseData['data'])) {
            $trip = $responseData['data'][0];
            $this->assertArrayHasKey('driver', $trip);
            $this->assertArrayHasKey('vehicle', $trip);
        }
    }

    /**
     * Test la récupération d'un trip spécifique
     */
    public function testShow(): void
    {
        $client = static::createClient();

        // Id d’un trip existant pour test (adapter selon ta BDD)
        $tripId = 1;

        $client->request('GET', self::API_BASE . '/' . $tripId);

        $this->assertResponseIsSuccessful(); // 200 OK
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('success', $responseData);
        $this->assertTrue($responseData['success']);

        $this->assertArrayHasKey('data', $responseData);
        $trip = $responseData['data'];

        // Vérifie les relations
        $this->assertArrayHasKey('driver', $trip);
        $this->assertArrayHasKey('preference', $trip['driver']);
        $this->assertArrayHasKey('reviews', $trip['driver']);
        $this->assertArrayHasKey('vehicle', $trip);
    }
}
