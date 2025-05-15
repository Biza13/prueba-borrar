<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Publicaciones;
use App\Entity\Comentarios;
use App\Entity\User;
use App\Form\PublicacionesType;
use App\Form\ComentariosType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

final class MyWallController extends AbstractController
{
    #[Route('/my/wall', name: 'app_my_wall')]
    public function index(EntityManagerInterface $em): Response
    {

        $todosUsuarios = $em->getRepository(User::class)->findAll();

        $user = $this->getUser();
        $publicacionesUsu = $user->getPublicaciones();

        //dd($publicacionesUsu);
        return $this->render('my_wall/index.html.twig', [
            'publicaciones' => $publicacionesUsu,
            'usuarios' => $todosUsuarios
        ]);
    }

    #[Route('/my/wall/crearPublicacion', name: 'app_crear_publicacion')]
    public function crearPuiblicaion(EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {

        $user = $this->getUser();

        $publicacion = new Publicaciones;

        $form = $this->createForm(PublicacionesType::class, $publicacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $imagenFile = $form->get('imagenPath')->getData();

            if ($imagenFile){
                //generar un nombre para la imagen
                $nombreOriginal = pathInfo($imagenFile->getClientOriginalName(), PATHINFO_FILENAME);
                $nombreSeguro = $slugger->slug($nombreOriginal);
                $nuevoNombre = $nombreSeguro.'-'.uniqid().'.'.$imagenFile->guessExtension();

                //Mover el archivo a la carpeta public/imgs
                $imagenFile->move($this->getParameter('images_directory'), $nuevoNombre);

                //asignar el nombre del archivo a la propiedad imagenPath
                $publicacion->setImagenPath($nuevoNombre);
            }

            $publicacion->setUsuario($user);
            $publicacion->setFechaPub(new \DateTime());

            $em->persist($publicacion);
            $em->flush();

            $this->addFlash('mensaje','La publicacion se ha grabado correctamente');
        }

        return $this->render('my_wall/crearPublicacion.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/my/wall/verComentarios/{id}', name: 'app_ver_comentarios')]
    public function verPublicacion(Publicaciones $pub): Response
    {

        //Hay que poner el toArray porque lo que eso devuelve es una coleccion y nosotros necesitamos que sea un array
        $comentarios = $pub->getComentarios()->toArray();

        //array_filter devuelve un array con los elementos de el array que cumplan la condicion fn significa function
        $comentariosAPublicaciones = array_filter($comentarios, fn($comentario) => $comentario->getComentarioResp() == null);
        $respuestasAComentarios = array_filter($comentarios, fn($comentario) => $comentario->getComentarioResp() != null);

        return $this->render('my_wall/verComentarios.html.twig', [
            'comentarios' => $comentariosAPublicaciones,
            'respuestasAComentarios' => $respuestasAComentarios,
            'publicacion' => $pub
        ]);

    }    

    #[Route('/my/wall/comentar/{id}', name: 'app_comentar')]
    public function comentar(EntityManagerInterface $em, Publicaciones $pub, Request $request): Response
    {

        $user = $this->getUser();

        $comentario = new Comentarios;

        //$form = $this->createForm(ComentariosType::class, $comentario,);
        $form = $this->createForm(ComentariosType::class, $comentario, [
            'es_comentario' => false,
            'comentario_resp' => null
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $comentario->setUsuario($user);
            $comentario->setPublicacion($pub);

            $em->persist($comentario);
            $em->flush();

            //mensaje de exito para el grabado del comentario
            $this->addFlash('mensaje','El comentario se ha grabado correctamente');
        }

        return $this->render('my_wall/comentarPublicacion.html.twig', [
            'publicacion' => $pub,
            'form' => $form,
        ]);

    }  

    #[Route('/my/wall/comentarComentario/{idPub}/{id}', name: 'app_comentar_comentario')]
    public function comentarComentario(int $idPub, int $id,/* Publicaciones $pub, Comentarios $com, */ EntityManagerInterface $em, Request $request): Response
    {
        //inyecto los datos de la url en los parametros de la función y los uso para 
        //cojer la publicacion correscta y el comentario correcto
        $pub = $em->getRepository(Publicaciones::class)->find($idPub);
        $com = $em->getRepository(Comentarios::class)->find($id);

        $user = $this->getUser();

        $respuesta = new Comentarios;

        //$form = $this->createForm(ComentariosType::class, $respuesta,);
        //basicamente aqui estoy creando un array en el formulario con dos valores es_comentario y comentarioResp
        $form = $this->createForm(ComentariosType::class, $respuesta, [
            'es_comentario' => true,    //este es para que no me saque el campo de comentarios
            'comentario_resp' => $com    //este el comentario que me paso con la url al que quiero responer
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $respuesta->setUsuario($user);
            $respuesta->setPublicacion($pub);
            $respuesta->setComentarioResp($com);

            $em->persist($respuesta);
            $em->flush();

            //mensaje de exito para el grabado del comentario
            $this->addFlash('mensaje','Rspuesta enviada correctamente');
        }

        return $this->render('my_wall/responderComentario.html.twig', [
            'publicacion' => $pub,
            'form' => $form,
            'comentario' => $com
        ]);

    }  

    #[Route('/my/wall/verMuroAjeno/{id}', name: 'app_ver_muro_ajeno')]
    public function verMuroAjeno(User $user): Response
    {

        $publicaciones = $user->getPublicaciones();

        return $this->render('my_wall/muroAjeno.html.twig', [
            'user' => $user,
            'publicaciones' => $publicaciones
        ]);

    }

}
