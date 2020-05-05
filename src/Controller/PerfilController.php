<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class PerfilController extends AbstractController
{
    /**
     * @Route("/perfil/{id}", name="perfil")
     */
    public function index(int $id)
    {
        $usuario = $this->obtenerUsuario($id);
        $publicaciones = $usuario->getPublicaciones();

        return $this->render('perfil/perfil.html.twig', [
            'controller_name' => 'PerfilController',
            'usuario' => $usuario,
            'publicaciones' => $publicaciones
        ]);
    }

    private function obtenerUsuario(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $usuario = $repository->findOneBy(['id' => $id]);

        return $usuario;
    }
}
