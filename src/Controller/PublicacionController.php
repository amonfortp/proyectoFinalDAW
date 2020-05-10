<?php

namespace App\Controller;

use App\Entity\Publicacion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
            $publicaciones = $this->obtenerPublicaciones();

            return $this->render('publicacion/publicaciones.html.twig', [
                'controller_name' => 'PublicacionController',
                'publicaciones' => $publicaciones
            ]);
        } else {
            return $this->render('publicacion/publicacion.html.twig', [
                'controller_name' => 'PublicacionController',
                'publicacion' => $publicacion
            ]);
        }
    }

    /**
     * @Route("/formPublicacion/{errorNum}", name="formPublicacion")
     */
    public function indexFormPublicaciones(int $errorNum = 0)
    {
        $error = $this->mensajeErrorCreacion($errorNum);
        return $this->render('publicacion/formPublicacion.html.twig', [
            'controller_name' => 'PublicacionController',
            'error' => $error
        ]);
    }

    /**
     * @Route("/formPublicacionRes", name="formPublicacionRes", methods={"POST"})
     */
    public function indexFormPublicacionesRes(Request $request)
    {
        $validar = $this->validarCreacion($request);

        if ($validar != 0) {
            return $this->redirectToRoute('formPublicacion', ['$errorNum' => $validar]);
        } else {
            return $this->redirectToRoute('perfil', ['id' => $this->getUser()->getId()]);
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

    private function mensajeErrorCreacion(int $error)
    {
        $mensaje = null;

        if ($error == 1) {
            $mensaje = "El nombre debe tener entre 3 y 25 caracteres";
        } else if ($error == 2) {
            $mensaje = "La contraseña debe tener almenos entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula y al menos una mayúscula.
            Puede tener otros símbolos.";
        } else if ($error == 3) {
            $mensaje = "La contraseña y la confirmación deben ser iguales";
        } else if ($error == 4) {
            $mensaje = "Tu contraseña actual es incorrecta o esta en blanco";
        }

        return $mensaje;
    }

    private function validarCreacion(Request $request): int
    {
        $error = 0;
        $titulo = $request->request->get("titulo");
        $desc = $request->request->get("descripcion");
        $etiquetas = $request->request->get("allEtiquetas");
        $etiqueta = explode("/", $etiquetas);
        $files = $_FILES["imgPubli"];


        if (strlen(str_replace(' ', '', $titulo)) < 3 || strlen(str_replace(' ', '', $titulo)) > 25) {
            $error = 1;
        } else if (strlen(str_replace(' ', '', $desc)) > 255) {
            $error = 2;
        } else if (str_replace(' ', '', $etiquetas) == "") {
            $error = 3;
        } else if ($files['size'] == 0) {
            $error = 4;
        }
        return $error;
    }

    private function crearPublicacion()
    {
    }
}
