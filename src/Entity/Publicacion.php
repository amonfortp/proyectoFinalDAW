<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=25)
     */
    private $nombrePublicacion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Activo;

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

    public function getNombrePublicacion(): ?string
    {
        return $this->nombrePublicacion;
    }

    public function setNombrePublicacion(string $nombrePublicacion): self
    {
        $this->nombrePublicacion = $nombrePublicacion;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->Activo;
    }

    public function setActivo(bool $Activo): self
    {
        $this->Activo = $Activo;

        return $this;
    }
}
