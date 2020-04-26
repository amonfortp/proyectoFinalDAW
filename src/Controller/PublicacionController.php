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
     * @Route("/publicaciones", name="publicaciones")
     */
    public function index()
    {
        $publicaciones = $this->obtenerPublicaciones();

        return $this->render('publicacion/publicaciones.html.twig', [
            'controller_name' => 'PublicacionController',
            'publicaciones' => $publicaciones
        ]);
    }

    /**
     * @Route("/publicacion/{id}", name="publicacion")
     */
    public function indexPublicacion(int $id = 0)
    {
        $publicacion = $this->obtenerPublicacion($id);

        if ($id == 0) {
            return $this->redirectToRoute('publicaciones');
        } else {
            return $this->render('publicacion/publicacion.html.twig', [
                'controller_name' => 'PublicacionController',
                'publicacion' => $publicacion
            ]);
        }
    }

    private function obtenerPublicaciones()
    {
        $repository = $this->getDoctrine()->getRepository(Publicacion::class);
        $publicaciones = $repository->findBy(['activo' => true], ['fechaPublicacion' => 'DESC']);

        return $publicaciones;
    }

    private function obtenerPublicacion(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Publicacion::class);
        $publicacion = $repository->findOneBy(['id' => $id]);

        return $publicacion;
    }
}
