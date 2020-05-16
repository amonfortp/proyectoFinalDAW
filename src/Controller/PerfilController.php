<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Chat;
use App\Entity\Messages;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Mercure\CookieGenerator;

/**
 * @IsGranted("ROLE_USER")
 */
class PerfilController extends AbstractController
{
    private $cookie;

    public function __construct(CookieGenerator $cookieGenerator)
    {
        $this->cookie = $cookieGenerator;
    }

    /**
     * @Route("/perfil/{id}", name="perfil")
     */
    public function index(int $id)
    {
        $usuario = $this->obtenerUsuario($id);
        $publicaciones = $usuario->getPublicaciones();

        $response = $this->render('perfil/perfil.html.twig', [
            'controller_name' => 'PerfilController',
            'usuario' => $usuario,
            'publicaciones' => $publicaciones,
            'navRed' => $this->comprobarChats()
        ]);

        $response->headers->setCookie($this->cookie->generate());

        return $response;
    }

    private function obtenerUsuario(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $usuario = $repository->findOneBy(['id' => $id]);

        return $usuario;
    }

    private function comprobarChats()
    {
        $allChats = $this->getDoctrine()->getRepository(Chat::class)->findAll();

        $aviso = 0;

        for ($i = 0; $i < count($allChats); $i++) {
            $mensaje = $this->getDoctrine()->getRepository(Messages::class)->findOneBy([
                "chat" => $allChats[$i],
                "visto" => false
            ]);
            if ($mensaje != null) {
                if ($mensaje->getUsuario()->getId() != $this->getUser()->getId()) {
                    $aviso = 1;
                    break;
                }
            }
        }

        return $aviso;
    }
}
