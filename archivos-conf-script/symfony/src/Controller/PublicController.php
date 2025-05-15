<?php

namespace App\Controller;

use App\Entity\Publicaciones;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

final class PublicController extends AbstractController
{
    #[Route('/public/users', name: 'app_public_users')]
    public function jsonUsuarios(EntityManagerInterface $em): JsonResponse //poner aqui el json response 
    {

        $usuarios = $em->getRepository(User::class)->findAll();

        //crear array vacio
        $arrUsuarios = [];

        //recorrer el los usuarios
        foreach ($usuarios as $usu){
            //creamos otro array con los datos del usuario
            $tupla = ['id' => $usu->getId(), 'nombre' => $usu->getUsername()];
            //lo añadimos al primer array
            array_push($arrUsuarios, $tupla);
        }

        return new JsonResponse([
            'usuarios' => $arrUsuarios
        ],JsonResponse::HTTP_OK);

        /* return $this->render('public/users/index.html.twig', [
            'controller_name' => 'PublicController',
        ]); */
    }

    #[Route('/public/publicaciones', name: 'app_public_publicaciones')]
    public function jsonPublicaciones(EntityManagerInterface $em): JsonResponse //poner aqui el json response 
    {

        $publicaciones = $em->getRepository(Publicaciones::class)->findAll();

        //crear array vacio
        $arrPublicaciones = [];

        //recorrer el los usuarios
        foreach ($publicaciones as $pub){
            //creamos otro array con los datos del usuario
            $tupla = ['id' => $pub->getId(), 'titulo' => $pub->getTitulo(), 'contenido' => $pub->getContenido()];
            //lo añadimos al primer array
            array_push($arrPublicaciones, $tupla);
        }

        return new JsonResponse([
            'Publicaciones' => $arrPublicaciones
        ],JsonResponse::HTTP_OK);

        /* return $this->render('public/users/index.html.twig', [
            'controller_name' => 'PublicController',
        ]); */
    }
}
