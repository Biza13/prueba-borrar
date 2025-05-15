<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class JwtSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        # Decodifica el token para obtener el usuario
        $user = $token->getUser();

        # Si no es un usuario de tipo User generamos una excepción o enviamos un mensaje
        if (!$user instanceof UserInterface) {
            # throw new \LogicException('El usuario debe implementar UserInterface.');
            return new JsonResponse(['error' => 'error'], JsonResponse::HTTP_ERROR);
        }

        # Creamos el token 
        $jwt = $this->jwtManager->create($user);
        #y lo devolvemos
        return new JsonResponse(['token' => $jwt], JsonResponse::HTTP_OK);
    }
}
