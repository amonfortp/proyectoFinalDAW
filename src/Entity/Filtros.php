<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FiltrosRepository")
 */
class Filtros
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $ordenFecha;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provincias")
     */
    private $provincia;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="filtros")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuarioProp;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Etiquetas")
     */
    private $etiqueta;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tipo;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $titulo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdenFecha(): ?string
    {
        return $this->ordenFecha;
    }

    public function setOrdenFecha(string $ordenFecha): self
    {
        $this->ordenFecha = $ordenFecha;

        return $this;
    }

    public function getProvincia(): ?Provincias
    {
        return $this->provincia;
    }

    public function setProvincia(?Provincias $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getUsuarioProp(): ?User
    {
        return $this->usuarioProp;
    }

    public function setUsuarioProp(?User $usuarioProp): self
    {
        $this->usuarioProp = $usuarioProp;

        return $this;
    }

    public function getEtiqueta(): ?Etiquetas
    {
        return $this->etiqueta;
    }

    public function setEtiqueta(?Etiquetas $etiqueta): self
    {
        $this->etiqueta = $etiqueta;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo;

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
}
