<?php 

namespace App\Service;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;

class JwtService
{
    private UserRepository $userRepository;
    private $jwtEncoder;

    public function __construct(JWTEncoderInterface $jwtEncoder, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->jwtEncoder = $jwtEncoder;
    }

    /**
     * Vérifie le token, décode le payload, récupère le user.
     * Lance une Exception si problème.
     */
    public function validate(Request $request)
    {
        $authHeader = $request->headers->get('Authorization');

        $jwt = $this->extractToken($authHeader);

        if (!$jwt) {
            throw new \Exception('Token manquant ou invalide');
        }

        $payload = $this->decodeToken($jwt);

        if (!$payload || !isset($payload['username'])) {
            throw new \Exception('Token invalide ou username manquant');
        }

        $username = $payload['username'];

        $user = $this->userRepository->findUserWithRelations($username);

        if (!$user) {
            throw new \Exception('Utilisateur non trouvé');
        }

        return $user;
    }

    /**
     * Décoder un JWT et retourner son contenu
     *
     * @param string $jwt
     * @return array|null
     */
    public function decodeToken(string $jwt): ?array
    {
        try {
            $data = $this->jwtEncoder->decode($jwt);
            return $data;
        } catch (\Exception $e) {
            // Token invalide
            return null;
        }
    }

    /**
     * Extraire le JWT depuis l'en-tête Authorization
     *
     * @param string|null $authorizationHeader
     * @return string|null
     */
    public function extractToken(?string $authorizationHeader): ?string
    {
        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            return null;
        }

        return substr($authorizationHeader, 7); // Supprime 'Bearer '
    }
}
