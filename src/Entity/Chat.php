<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 */
class Chat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $topic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario2;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Messages", mappedBy="chat", orphanRemoval=true)
     */
    private $mensajes;

    public function __construct()
    {
        $this->mensajes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getUsuario1(): ?User
    {
        return $this->usuario1;
    }

    public function setUsuario1(?User $usuario1): self
    {
        $this->usuario1 = $usuario1;

        return $this;
    }

    public function getUsuario2(): ?User
    {
        return $this->usuario2;
    }

    public function setUsuario2(?User $usuario2): self
    {
        $this->usuario2 = $usuario2;

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getMensajes(): Collection
    {
        return $this->mensajes;
    }

    public function addMensaje(Messages $mensaje): self
    {
        if (!$this->mensajes->contains($mensaje)) {
            $this->mensajes[] = $mensaje;
            $mensaje->setChat($this);
        }

        return $this;
    }

    public function removeMensaje(Messages $mensaje): self
    {
        if ($this->mensajes->contains($mensaje)) {
            $this->mensajes->removeElement($mensaje);
            // set the owning side to null (unless already changed)
            if ($mensaje->getChat() === $this) {
                $mensaje->setChat(null);
            }
        }

        return $this;
    }
}
