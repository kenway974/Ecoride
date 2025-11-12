<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) { $this->em = $em; }

    #[Route('/api/user/role', name: 'api_user_role', methods: ['PUT'])]
    public function updateRole(Request $request): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $role = $data['role'] ?? 'passager';
        $user->setRoles(['ROLE_CHAUFFEUR']);
        $this->em->flush();

        return new JsonResponse(['message' => 'Rôle mis à jour', 'role' => $role]);
    }
}
