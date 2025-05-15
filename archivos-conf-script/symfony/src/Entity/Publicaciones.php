<?php

namespace App\Entity;

use App\Repository\PublicacionesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicacionesRepository::class)]
class Publicaciones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    private ?string $contenido = null;

    //para la imagen
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagenPath = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaPub = null;

    #[ORM\ManyToOne(inversedBy: 'publicaciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $usuario = null;

    /**
     * @var Collection<int, Comentarios>
     */
    #[ORM\OneToMany(targetEntity: Comentarios::class, mappedBy: 'publicacion', orphanRemoval: true)]
    private Collection $comentarios;

    public function __construct()
    {
        $this->comentarios = new ArrayCollection();
    }

    //Para las imagenes
    public function getImagenPath (): ?string
    {
        return $this->imagenPath;
    }

    public function setImagenPath (?string $imagenPath):self
    {
        $this->imagenPath = $imagenPath;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): static
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getFechaPub(): ?\DateTimeInterface
    {
        return $this->fechaPub;
    }

    public function setFechaPub(\DateTimeInterface $fechaPub): static
    {
        $this->fechaPub = $fechaPub;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @return Collection<int, Comentarios>
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(Comentarios $comentario): static
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios->add($comentario);
            $comentario->setPublicacion($this);
        }

        return $this;
    }

    public function removeComentario(Comentarios $comentario): static
    {
        if ($this->comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getPublicacion() === $this) {
                $comentario->setPublicacion(null);
            }
        }

        return $this;
    }
}
