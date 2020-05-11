<?php

namespace App\Controller;

use App\Entity\Etiquetas;
use App\Entity\Publicacion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;

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
            return $this->redirectToRoute('formPublicacion', ['errorNum' => $validar]);
        } else {
            $this->crearPublicacion($request);
            return $this->redirectToRoute('perfil', ['id' => $this->getUser()->getId()]);
        }
    }

    /**
     * @Route("/modifPublicacion/{datos}", name="modifPublicacion")
     */
    public function indexModifPublicacion(string $datos)
    {
        $id = (int) explode("-", $datos)[0];
        $errorNum = (int) explode("-", $datos)[1];
        $publicacion = $this->obtenerPublicacion($id);
        $error = $this->mensajeErrorCreacion($errorNum);

        return $this->render('publicacion/modifPublicacion.html.twig', [
            'controller_name' => 'PublicacionController',
            'error' => $error,
            'publicacion' => $publicacion
        ]);
    }

    /**
     * @Route("/modifPublicacionRes", name="modifPublicacionRes", methods={"POST"})
     */
    public function modifPublicacionRes(Request $request)
    {
        $validar = $this->validarCreacion($request);

        if ($validar != 0) {
            return $this->redirectToRoute('formPublicacion', ['errorNum' => $validar]);
        } else {
            $this->crearPublicacion($request);
            return $this->redirectToRoute('perfil', ['id' => $this->getUser()->getId()]);
        }
    }

    /**
     * @Route("/eliminarPubli/{id}", name="eliminarPubli")
     */
    public function eliminarPublicacion(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Publicacion::class);
        $publicacion = $repository->findOneBy(['id' => $id]);

        $entityManager->remove($publicacion);
        $entityManager->flush();

        return $this->redirectToRoute('perfil', ['id' => $this->getUser()->getId()]);
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
            $mensaje = "El el titulo debe tener entre 3 y 50 caracteres";
        } else if ($error == 2) {
            $mensaje = "La descripción no puede superar los 255 caracteres";
        } else if ($error == 3) {
            $mensaje = "Como minimo debes tener una etiqueta";
        } else if ($error == 4) {
            $mensaje = "Como minimo debes subir una imagen";
        }

        return $mensaje;
    }

    private function validarCreacion(Request $request): int
    {
        $error = 0;
        $titulo = $request->request->get("titulo");
        $desc = $request->request->get("descripcion");
        $etiquetas = $request->request->get("allEtiquetas");
        $files = $request->request->get("numImg");


        if (strlen(str_replace(' ', '', $titulo)) < 3 || strlen($titulo) > 25) {
            $error = 1;
        } else if (strlen(str_replace(' ', '', $desc)) > 255) {
            $error = 2;
        } else if (str_replace(' ', '', $etiquetas) == "") {
            $error = 3;
        } else {
            if ($files == 0) {
                $error = 4;
            } else {
                $error = 4;
                for ($i = 1; $i <= $files; $i++) {
                    if ($_FILES["imgPubli" . $i]["size"] != 0) {
                        $error = 0;
                    }
                }
            }
        }
        return $error;
    }

    private function crearPublicacion(Request $request)
    {
        $filesystem = new Filesystem();
        $arrayImages = [];
        $numFiles = $request->request->get("numImg");
        $entityManager = $this->getDoctrine()->getManager();

        $publi = new Publicacion();

        $etiquetas = explode("/", $request->request->get("allEtiquetas"));

        for ($i = 0; $i < count($etiquetas); $i++) {
            $etiqueta = new Etiquetas();
            $etiqueta->setEtiqueta($etiquetas[$i]);
            $entityManager->persist($etiqueta);

            $publi->addEtiquetum($etiqueta);
        }

        $publi->setTitulo($request->request->get("titulo"));
        $publi->setDescripcion($request->request->get("descripcion"));
        $publi->setUsuario($this->getUser());
        $publi->setTipo($request->request->get("tipo"));

        $carpeta = "img/" . $this->getUser()->getEmail() . "/" . str_replace(' ', '', $publi->getTitulo());
        $numPubli = 0;
        while ($filesystem->exists($carpeta)) {
            $numPubli++;
            $carpeta = $carpeta . $numPubli;
        }

        $filesystem->mkdir($carpeta, 0777);

        for ($i = 1; $i <= $numFiles; $i++) {
            if ($_FILES["imgPubli" . $i]["size"] != 0) {
                $file = $_FILES["imgPubli" . $i];

                $fichero = "/" . str_replace(' ', '', $publi->getTitulo()) . $i . ".jpg";
                $ruta = $carpeta . $fichero;
                move_uploaded_file($file["tmp_name"], "/home/dwes/proyectoFinal/public/" . $ruta);

                array_push($arrayImages, $ruta);
            }
        }

        $publi->setImagenes($arrayImages);

        $entityManager->persist($publi);
        return $entityManager->flush();
    }
}
