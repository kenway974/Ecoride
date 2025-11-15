<?php 

namespace App\Service;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class JwtService
{
    private $jwtEncoder;

    public function __construct(JWTEncoderInterface $jwtEncoder)
    {
        $this->jwtEncoder = $jwtEncoder;
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
