<?php

namespace App\Entity;

use App\Repository\ComentariosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComentariosRepository::class)]
class Comentarios
{

    //getComentarioResp(): devolvera el comentarioal que se le responde
    //getComentariosRes(): devolvera los comentarios que se le hayan hecho al comentario

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenidoCom = null;

    #[ORM\ManyToOne(inversedBy: 'comentarios')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $usuario = null;

    #[ORM\ManyToOne(inversedBy: 'comentarios')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Publicaciones $publicacion = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'comentariosResp')]
    private ?self $comentarioResp = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'comentarioResp')]
    private Collection $comentariosResp;

    public function __construct()
    {
        $this->comentariosResp = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenidoCom(): ?string
    {
        return $this->contenidoCom;
    }

    public function setContenidoCom(string $contenidoCom): static
    {
        $this->contenidoCom = $contenidoCom;

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

    public function getPublicacion(): ?Publicaciones
    {
        return $this->publicacion;
    }

    public function setPublicacion(?Publicaciones $publicacion): static
    {
        $this->publicacion = $publicacion;

        return $this;
    }

    public function getComentarioResp(): ?self
    {
        return $this->comentarioResp;
    }

    public function setComentarioResp(?self $comentarioResp): static
    {
        $this->comentarioResp = $comentarioResp;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getComentariosResp(): Collection
    {
        return $this->comentariosResp;
    }

    public function addComentariosResp(self $comentariosResp): static
    {
        if (!$this->comentariosResp->contains($comentariosResp)) {
            $this->comentariosResp->add($comentariosResp);
            $comentariosResp->setComentarioResp($this);
        }

        return $this;
    }

    public function removeComentariosResp(self $comentariosResp): static
    {
        if ($this->comentariosResp->removeElement($comentariosResp)) {
            // set the owning side to null (unless already changed)
            if ($comentariosResp->getComentarioResp() === $this) {
                $comentariosResp->setComentarioResp(null);
            }
        }

        return $this;
    }
}
