<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessagesRepository")
 */
class Messages
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
    private $mensajes;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timeEnvio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="mensajes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chat", inversedBy="mensajes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Publicacion")
     */
    private $publicacion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visto;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMensajes(): ?string
    {
        return $this->mensajes;
    }

    public function setMensajes(string $mensajes): self
    {
        $this->mensajes = $mensajes;

        return $this;
    }

    public function getTimeEnvio(): ?\DateTimeInterface
    {
        return $this->timeEnvio;
    }

    public function setTimeEnvio(\DateTimeInterface $timeEnvio): self
    {
        $this->timeEnvio = $timeEnvio;

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

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }

    public function getPublicacion(): ?Publicacion
    {
        return $this->publicacion;
    }

    public function setPublicacion(?Publicacion $publicacion): self
    {
        $this->publicacion = $publicacion;

        return $this;
    }

    public function getVisto(): ?bool
    {
        return $this->visto;
    }

    public function setVisto(bool $visto): self
    {
        $this->visto = $visto;

        return $this;
    }
}
