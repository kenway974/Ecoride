<?php

namespace App\Service;

use App\Entity\Preference;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PreferenceService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function updatePreferences(User $user, array $data): Preference
    {
        $preference = $user->getPreference() ?? new Preference();
        $preference->setUser($user);
        $preference->setAnimals($data['animals'] ?? false);
        $preference->setSmoke($data['smoke'] ?? false);
        $preference->setFood($data['food'] ?? false);
        $preference->setIsCustom($data['is_custom'] ?? false);
        $preference->setOptions($data['options'] ?? []);
        $preference->setCreatedAt($preference->getCreatedAt() ?? new \DateTimeImmutable());
        $preference->setUpdatedAt(new \DateTimeImmutable());

        $this->em->persist($preference);
        $this->em->flush();

        return $preference;
    }
}
