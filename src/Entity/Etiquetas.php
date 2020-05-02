<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EtiquetasRepository")
 */
class Etiquetas
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $etiqueta;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Publicacion", mappedBy="etiqueta")
     */
    private $publicaciones;

    public function __construct()
    {
        $this->publicaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtiqueta(): ?string
    {
        return $this->etiqueta;
    }

    public function setEtiqueta(string $etiqueta): self
    {
        $this->etiqueta = $etiqueta;

        return $this;
    }

    /**
     * @return Collection|Publicacion[]
     */
    public function getPublicaciones(): Collection
    {
        return $this->publicaciones;
    }

    public function addPublicacione(Publicacion $publicacione): self
    {
        if (!$this->publicaciones->contains($publicacione)) {
            $this->publicaciones[] = $publicacione;
            $publicacione->addEtiquetum($this);
        }

        return $this;
    }

    public function removePublicacione(Publicacion $publicacione): self
    {
        if ($this->publicaciones->contains($publicacione)) {
            $this->publicaciones->removeElement($publicacione);
            $publicacione->removeEtiquetum($this);
        }

        return $this;
    }
}
