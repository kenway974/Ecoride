<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    /**
     * ✅ Route pour mettre à jour le rôle d’un utilisateur connecté
     */
    #[Route('/api/user/role', name: 'api_user_role', methods: ['PUT'])]
    #[IsGranted(['ROLE_USER'])]
    public function updateRole(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], 401);
        }

        $data = json_decode($request->getContent(), true);
        $role = $data['role'] ?? 'ROLE_PASSAGER';

        $user->setRoles([$role]);
        $this->em->flush();

        return new JsonResponse(['message' => 'Rôle mis à jour', 'role' => $role]);
    }
}
