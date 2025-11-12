<?php

namespace App\Controller;

use App\Entity\Preference;
use App\Service\PreferenceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PreferenceController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) { $this->em = $em; }

    #[Route('/api/user/preferences', name: 'api_user_preferences', methods: ['PUT'])]
    public function updatePreferences(Request $request, PreferenceService $preferenceService): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $preference = $preferenceService->updatePreferences($user, $data);

        return new JsonResponse([
            'animals' => $preference->getAnimals(),
            'smoke' => $preference->getSmoke(),
            'food' => $preference->getFood(),
            'is_custom' => $preference->getIsCustom(),
            'options' => $preference->getOptions(),
            'created_at' => $preference->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $preference->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

}
