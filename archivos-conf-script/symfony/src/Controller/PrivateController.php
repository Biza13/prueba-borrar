<?php

namespace App\Controller;

use App\Entity\Comentarios;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class PrivateController extends AbstractController
{
    //PARA OBTENER EL TOKEN
    #[Route('/cliente/login', name: 'app_cliente_login')]
    public function login(HttpClientInterface $httpClient, Request $request): Response
    {
        # Obtenemos el username y el password pasados como parámetros en la url al cliente
        $username = $request->query->get('username');
        $password = $request->query->get('password');

        # Lanzamos la petición proporcionando el username y el password
        $response = $httpClient->request('POST', 'http://localhost:8000/api/login', [
            'json' => [
                'username' => $username,
                'password' => $password
            ]
        ]);
        # Decodificamos la respuesta para obtener el token
        $json = json_decode($response->getContent(),true);
        # Guardamos el token en la sesión
        $request->getSession()->set('token', $json['token']);
        # Devolvemos, también, el token como respuesta al navegador
        return new Response($json['token']);
            
    }

    #[Route('/api/comentarios', name: 'app_api_jsoncomentarios', methods:['GET', 'POST'])]
    public function comentarios(EntityManagerInterface $em, Request $request, Security $security): JsonResponse
    {
        # Extraemos el cliente a partir de la decodificación del token
        $usuario = $security->getUser();

        # Obtenemos los pedidos del cliente
        $comentarios = $em->getRepository(Comentarios::class)->findByField('usuario', $usuario->getId());
        $datos = array_map(function($comentario) {
            return
            [
                'id' => $comentario->getId(),
                'fecha' => $comentario->getContenidoCom()
            ];
        }, $comentarios);

        return new JsonResponse($datos);
      
    }

}
