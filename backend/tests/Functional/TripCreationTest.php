<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Entity\Vehicle;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TripCreationTest extends WebTestCase
{
    public function testCreateTripFunctional(): void
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $driver = new User();
        $driver->setEmail('driver@example.com');
        $driver->setRoles(['ROLE_CHAUFFEUR']);
        $driver->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $entityManager->persist($driver);

        $vehicle = new Vehicle();
        $vehicle->setBrand('Peugeot');
        $vehicle->setModel('208');
        $vehicle->setPlate('AB-123-CD');
        $vehicle->setOwner($driver);
        $entityManager->persist($vehicle);

        $entityManager->flush();

        $client->loginUser($driver);

        $payload = [
            'vehicleId' => $vehicle->getId(),
            'startCity' => 'Lyon',
            'arrivalCity' => 'Paris',
            'startAddress' => 'Place Bellecour',
            'arrivalAddress' => 'Gare du Nord',
            'departureDate' => '2025-11-15',
            'departureTime' => '08:00',
            'arrivalDate' => '2025-11-15',
            'arrivalTime' => '12:00',
            'seatsRemaining' => 3,
            'price' => 25.5,
            'isEcological' => true,
            'description' => 'Voyage confortable',
            'luggage' => 'Bagages moyens'
        ];

        $client->request(
            'POST',
            '/api/trips',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('tripId', $responseData);

        $trip = $entityManager->getRepository('App\Entity\Trip')->find($responseData['tripId']);
        $this->assertNotNull($trip);
        $this->assertSame('Lyon', $trip->getStartCity());
        $this->assertSame('Paris', $trip->getArrivalCity());
        $this->assertSame($driver->getId(), $trip->getDriver()->getId());
        $this->assertSame($vehicle->getId(), $trip->getVehicle()->getId());
    }
}
