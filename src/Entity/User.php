<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $nickName;

    /**
     * @ORM\Column(type="date")
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rol;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provincias", inversedBy="users")
     */
    private $provincia;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagenPerfil;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Publicacion", mappedBy="usuario", orphanRemoval=true)
     */
    private $publicaciones;

    /**
     * @ORM\Column(type="integer")
     */
    private $reputacion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Messages", mappedBy="usuario", orphanRemoval=true)
     */
    private $mensajes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Filtros", mappedBy="usuarioProp", orphanRemoval=true)
     */
    private $filtros;


    public function __construct()
    {
        $this->imagenPerfil = "img/comun/circulo.png";
        $this->lastLogin = new \DateTime();
        $this->publicaciones = new ArrayCollection();
        $this->reputacion = 0;
        $this->mensajes = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->filtros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function setNickName(string $nickName): self
    {
        $this->nickName = $nickName;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getRol(): ?int
    {
        return $this->rol;
    }

    public function setRol(?int $rol): self
    {
        $this->rol = $rol;

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

    public function getImagenPerfil(): ?string
    {
        return $this->imagenPerfil;
    }

    public function setImagenPerfil(string $imagenPerfil): self
    {
        $this->imagenPerfil = $imagenPerfil;

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
            $publicacione->setUsuario($this);
        }

        return $this;
    }

    public function removePublicacione(Publicacion $publicacione): self
    {
        if ($this->publicaciones->contains($publicacione)) {
            $this->publicaciones->removeElement($publicacione);
            // set the owning side to null (unless already changed)
            if ($publicacione->getUsuario() === $this) {
                $publicacione->setUsuario(null);
            }
        }

        return $this;
    }

    public function getReputacion(): ?int
    {
        return $this->reputacion;
    }

    public function setReputacion(int $reputacion): self
    {
        $this->reputacion = $reputacion;

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
            $mensaje->setUsuario($this);
        }

        return $this;
    }

    public function removeMensaje(Messages $mensaje): self
    {
        if ($this->mensajes->contains($mensaje)) {
            $this->mensajes->removeElement($mensaje);
            // set the owning side to null (unless already changed)
            if ($mensaje->getUsuario() === $this) {
                $mensaje->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Filtros[]
     */
    public function getFiltros(): Collection
    {
        return $this->filtros;
    }

    public function addFiltro(Filtros $filtro): self
    {
        if (!$this->filtros->contains($filtro)) {
            $this->filtros[] = $filtro;
            $filtro->setUsuarioProp($this);
        }

        return $this;
    }

    public function removeFiltro(Filtros $filtro): self
    {
        if ($this->filtros->contains($filtro)) {
            $this->filtros->removeElement($filtro);
            // set the owning side to null (unless already changed)
            if ($filtro->getUsuarioProp() === $this) {
                $filtro->setUsuarioProp(null);
            }
        }

        return $this;
    }
}
