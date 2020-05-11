<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PublicacionRepository")
 */
class Publicacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="publicaciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="array")
     */
    private $imagenes = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaPublicacion;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tipo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Etiquetas", inversedBy="publicaciones")
     */
    private $etiqueta;

    public function __construct()
    {
        $this->fechaPublicacion = new \DateTime();
        $this->etiqueta = new ArrayCollection();
        $this->activo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getImagenes(): ?array
    {
        return $this->imagenes;
    }

    public function setImagenes(array $imagenes): self
    {
        $this->imagenes = $imagenes;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    public function setFechaPublicacion(\DateTimeInterface $fechaPublicacion): self
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return Collection|Etiquetas[]
     */
    public function getEtiqueta(): Collection
    {
        return $this->etiqueta;
    }

    public function addEtiquetum(Etiquetas $etiquetum): self
    {
        if (!$this->etiqueta->contains($etiquetum)) {
            $this->etiqueta[] = $etiquetum;
        }

        return $this;
    }

    public function removeEtiquetum(Etiquetas $etiquetum): self
    {
        if ($this->etiqueta->contains($etiquetum)) {
            $this->etiqueta->removeElement($etiquetum);
        }

        return $this;
    }
}
