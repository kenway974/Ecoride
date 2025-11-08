<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

final class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? null;
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$username || !$password) {
            return new JsonResponse(['message' => 'Email, username et password requis'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['message' => 'Email invalide'], 400);
        }

        if (strlen($password) < 8) {
            return new JsonResponse(['message' => 'Mot de passe trop court'], 400);
        }


        // Vérifier si l’email existe déjà
        $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            return new JsonResponse(['message' => 'Cet email est déjà utilisé'], 400);
        }

        $user = new User();
        $user->setEmail($email)
             ->setUsername($username)
             ->setPassword($passwordHasher->hashPassword($user, $password))
             ->setRoles(['ROLE_USER'])
             ->setCredits(20)
             ->setIsActive(true)
             ->setCreatedAt(new \DateTimeImmutable())
             ->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($user);
        $em->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'message' => 'Utilisateur créé avec succès'
        ]);
    }
}
