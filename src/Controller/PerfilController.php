<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Chat;
use App\Entity\Messages;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Mercure\CookieGenerator;
use App\Service\PerfilService;

/**
 * @IsGranted("ROLE_USER")
 */
class PerfilController extends AbstractController
{
    private $cookie;
    private $service;

    public function __construct(CookieGenerator $cookieGenerator, PerfilService $service)
    {
        $this->cookie = $cookieGenerator;
        $this->service = $service;
    }

    /**
     * @Route("/perfil/{id}", name="perfil")
     */
    public function index(int $id)
    {
        $usuario = $this->service->obtenerUsuario($id);
        $publicaciones = $usuario->getPublicaciones();

        $response = $this->render('perfil/perfil.html.twig', [
            'controller_name' => 'PerfilController',
            'usuario' => $usuario,
            'publicaciones' => $publicaciones,
            'navRed' => $this->service->comprobarChats()
        ]);

        $response->headers->setCookie($this->cookie->generate());

        return $response;
    }
}
