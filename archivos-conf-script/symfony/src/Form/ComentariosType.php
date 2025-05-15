<?php

namespace App\Form;

use App\Entity\Comentarios;
use App\Entity\Publicaciones;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ComentariosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        if ($options['es_comentario']){
            $builder->add('comentarioResp', EntityType::class, [
                'class' => Comentarios::class,
                'choice_label' => 'id',
                'data' => $options['comentario_resp'],    //aqui cojo como valor predeterminado el valor del array
                'attr' => ['style' => 'display: none;']     //ocultarlo
            ]);
        }

        $builder
            ->add('contenidoCom', TextareaType::class, [
                'label' => false,
            ])
            /* ->add('usuario', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ]) */
            /* ->add('publicacion', EntityType::class, [
                'class' => Publicaciones::class,
                'choice_label' => 'id',
            ]) */
            /* ->add('comentarioResp', EntityType::class, [
                'class' => Comentarios::class,
                'choice_label' => 'contenidoCom',
            ]) */
            ->add('Comentar', SubmitType::class, [
                'attr' => [
                'class' => 'btn btn-lg btn-primary'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comentarios::class,
            // lo que le pasemos al formulario con un array hay que definirlo aqui
            'es_comentario' => false,
            'comentario_resp' => null
        ]);
    }
}
