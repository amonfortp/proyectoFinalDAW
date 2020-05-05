<?php

namespace App\Controller;

use App\Entity\Provincias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class AjustesController extends AbstractController
{
    /**
     * @Route("/ajustes/{id}", name="ajustes", defaults={"id": 0, "errorNum": 0})
     */
    public function index(int $id = 0, int $errorNum)
    {
        $perfil = "false";
        $filtros = "false";
        $provincias = $this->obtenerProvincias();
        $error = $this->mensajeError($errorNum);

        if ($id == 1) {
            $perfil = "true";
        } else if ($filtros) {
            $filtros = "true";
        }

        return $this->render('ajustes/ajustes.html.twig', [
            'controller_name' => 'AjustesController',
            'perfil' => $perfil,
            'filtros' => $filtros,
            'provincias' => $provincias,
            'error' => $error
        ]);
    }

    /**
     * @Route("/modificarPerfil", name="modificarPerfil", methods={"POST"})
     */
    public function modificarPerfil()
    {
        return $this->redirectToRoute('ajustes', ['id' => 0, 'errorNum' => 0]);
    }

    private function obtenerProvincias()
    {
        $repository = $this->getDoctrine()->getRepository(Provincias::class);

        return $repository->findAll();
    }

    private function mensajeError(int $error)
    {
        $mensaje = null;

        if ($error == 1) {
            $mensaje = "El nombre debe tener entre 6 y 25 caracteres";
        } else if ($error == 2) {
            $mensaje = "El formato de correo electrónico (email) es erroneo";
        } else if ($error == 3) {
            $mensaje = "Este correo ya esta en uso";
        } else if ($error == 4) {
            $mensaje = "La contraseña debe tener al entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula y al menos una mayúscula.
            Puede tener otros símbolos.";
        } else if ($error == 5) {
            $mensaje = "La contraseña y la confirmación deben ser iguales";
        } else if ($error == 6) {
            $mensaje = "Los campos obligatorios no pueden estar vacios";
        } else if ($error == 7) {
            $mensaje = "Debes aceptar las condiciones de uso";
        }

        return $mensaje;
    }
}
