<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Symfony\Component\HttpFoundation\Cookie;
use App\Entity\User;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class LoginController extends AbstractController
{
    
#[Route('/api/login', name: 'api_login', methods: ['POST'])]
public function login(
    Request $request,
    JWTTokenManagerInterface $jwtManager,
    UserProviderInterface $userProvider,
    RefreshTokenManagerInterface $refreshTokenManager,
    UserPasswordHasherInterface $passwordHasher
): JsonResponse {
    $data = json_decode($request->getContent(), true);
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    if (!$email || !$password) {
        return new JsonResponse(['message' => 'Email et password requis'], 400);
    }

    try {
            /** @var User $user */
            $user = $userProvider->loadUserByIdentifier($email);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Email incorrect'], 401);
        }

        // Vérifie le mot de passe correctement
        if (!$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['message' => 'Mot de passe incorrect'], 401);
        }

    $token = $jwtManager->create($user);

    // Genrer refresh token
    $refreshToken = new RefreshToken();
    $refreshToken->setUsername($user->getUserIdentifier());
    $refreshToken->setValid(new \DateTime('+1 month')); // durée de vie
    $refreshTokenManager->save($refreshToken);

    // Refresh Token en cookie
    $cookie = Cookie::create(
        'REFRESH_TOKEN',                   // name
        $refreshToken->getRefreshToken(),  // value
        $refreshToken->getValid(),         // expires at
        '/api/token/refresh',              // path
        null,                              // domain
        false,                              // secure(true en prod)
        true,                              // httpOnly
        false,                             // raw
        Cookie::SAMESITE_LAX               // sameSite
    );

    $response = new JsonResponse(['token' => $token]);
    $response->headers->setCookie($cookie);

    return $response;
}
}
