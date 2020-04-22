<?php

namespace App\Controller;

use App\Entity\Publicacion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class PublicacionController extends AbstractController
{
    /**
     * @Route("/publicacion", name="publicacion")
     */
    public function index()
    {
        $publicaciones = $this->obtenerPublicaciones();

        return $this->render('publicacion/publicacion.html.twig', [
            'controller_name' => 'PublicacionController',
            'publicaciones' => $publicaciones
        ]);
    }

    private function obtenerPublicaciones()
    {
        $repository = $this->getDoctrine()->getRepository(Publicacion::class);
        $publicaciones = $repository->findBy(['activo' => true], ['fechaPublicacion' => 'DESC']);

        return $publicaciones;
    }
}
