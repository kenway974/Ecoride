<?php

namespace App\EventListener;


use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;

class JWTAuthenticationSuccessListener
{
    public function __construct()
    {
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();

        /* Créer un refresh token
        $refreshToken = new RefreshToken();
        $refreshToken->setUsername($user->getUserIdentifier());
        $refreshToken->setValid(new \DateTime('+1 month'));
        $this->refreshTokenManager->save($refreshToken);

        // Ajouter le cookie HttpOnly
        $cookie = Cookie::create(
            'REFRESH_TOKEN',
            $refreshToken->getRefreshToken(),
            $refreshToken->getValid(),
            '/api/token/refresh',
            null,
            false,   // true en prod HTTPS
            true,    // httpOnly
            false,
            Cookie::SAMESITE_LAX
        );

        $event->getResponse()->headers->setCookie($cookie)*/;
    }
}
