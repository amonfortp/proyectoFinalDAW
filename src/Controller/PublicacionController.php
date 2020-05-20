<?php

namespace App\Controller;

use App\Entity\Etiquetas;
use App\Entity\Publicacion;
use App\Entity\Provincias;
use App\Entity\Chat;
use App\Entity\Filtros;
use App\Entity\Messages;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use App\Mercure\CookieGenerator;

/**
 * @IsGranted("ROLE_USER")
 */
class PublicacionController extends AbstractController
{
    private $cookie;

    public function __construct(CookieGenerator $cookieGenerator)
    {
        $this->cookie = $cookieGenerator;
    }

    /**
     * @Route("/publicaciones/{id}", name="publicaciones")
     */
    public function index(int $id = -1)
    {
        $filtros = $this->getUser()->getFiltros();

        if ($id < 0) {
            $publicaciones = $this->aplicarFiltro($id);
        } else {
            $publicaciones = $this->aplicarFiltro($id, $filtros[$id]);
        }



        $response = $this->render('publicacion/publicaciones.html.twig', [
            'controller_name' => 'PublicacionController',
            'publicaciones' => $publicaciones,
            'navRed' => $this->comprobarChats(),
            'provincias' => $this->obtenerProvincias(),
            'idFiltro' => $id,
            'filtros' => $filtros
        ]);

        $response->headers->setCookie($this->cookie->generate());

        return $response;
    }

    /**
     * @Route("/publicacion/{id}", name="publicacion")
     */
    public function indexPublicacion(int $id = 0)
    {
        $publicacion = $this->obtenerPublicacion($id);

        if ($id == 0) {
            $response = $this->redirectToRoute('publicaciones', ['id' => 0]);
        } else {
            $response = $this->render('publicacion/publicacion.html.twig', [
                'controller_name' => 'PublicacionController',
                'publicacion' => $publicacion,
                'navRed' => $this->comprobarChats()
            ]);

            $response->headers->setCookie($this->cookie->generate());
        }



        return $response;
    }

    /**
     * @Route("/formPublicacion/{errorNum}", name="formPublicacion")
     */
    public function indexFormPublicaciones(int $errorNum = 0)
    {
        $error = $this->mensajeErrorCreacion($errorNum);

        $response = $this->render('publicacion/formPublicacion.html.twig', [
            'controller_name' => 'PublicacionController',
            'error' => $error,
            'navRed' => $this->comprobarChats()
        ]);

        $response->headers->setCookie($this->cookie->generate());

        return $response;
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

        $response = $this->render('publicacion/modifPublicacion.html.twig', [
            'controller_name' => 'PublicacionController',
            'error' => $error,
            'publicacion' => $publicacion,
            'navRed' => $this->comprobarChats()
        ]);

        $response->headers->setCookie($this->cookie->generate());

        return $response;
    }

    /**
     * @Route("/modifPublicacionRes", name="modifPublicacionRes", methods={"POST"})
     */
    public function modifPublicacionRes(Request $request)
    {
        $validar = $this->validarModificacion($request);
        $datos = $request->request->get("idPubli") . "-" . $validar;
        if ($validar != 0) {
            return $this->redirectToRoute('modifPublicacion', ['datos' => $datos]);
        } else {
            $this->modificarPublicacion($request);
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
        $etiquetas = $publicacion->getEtiqueta();

        $this->deleteDir("/home/dwes/proyectoFinal/public/img/" . $publicacion->getUsuario()->getEmail() . "/" . explode("/", $publicacion->getImagenes()[0])[2]);

        $entityManager->remove($publicacion);
        for ($i = 0; $i < count($etiquetas); $i++) {
            $entityManager->remove($etiquetas[$i]);
        }
        $entityManager->flush();

        return $this->redirectToRoute('perfil', ['id' => $this->getUser()->getId()]);
    }

    private static function deleteDir($dirPath)
    {
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    private function obtenerPublicaciones(String $orden = 'DESC')
    {
        $repository = $this->getDoctrine()->getRepository(Publicacion::class);
        $publicaciones = $repository->findBy(['activo' => true], ['fechaPublicacion' => $orden]);

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
            $mensaje = "El titulo debe tener entre 3 y 18 caracteres";
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


        if (strlen(str_replace(' ', '', $titulo)) < 3 || strlen($titulo) > 18) {
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

    private function validarModificacion(Request $request): int
    {
        $error = 0;
        $publicacion = $this->obtenerPublicacion($request->request->get("idPubli"));
        $titulo = $request->request->get("titulo");
        $desc = $request->request->get("descripcion");
        $etiquetas = $request->request->get("allEtiquetas");
        $files = $request->request->get("numImg");


        if (strlen(str_replace(' ', '', $titulo)) < 3 || strlen($titulo) > 18) {
            $error = 1;
        } else if (strlen(str_replace(' ', '', $desc)) > 255) {
            $error = 2;
        } else if (str_replace(' ', '', $etiquetas) == "") {
            $error = 3;
        } else {
            $aux = 0;
            for ($i = 0; $i < count($publicacion->getImagenes()); $i++) {
                if ($request->request->get("delete" . $i) == "on") {
                    $aux++;
                }
            }
            if ($aux == count($publicacion->getImagenes())) {
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
        if (strlen(str_replace(' ', '', $request->request->get("descripcion"))) == 0) {
            $publi->setDescripcion("Sin descripción");
        } else {
            $publi->setDescripcion($request->request->get("descripcion"));
        }

        $publi->setUsuario($this->getUser());
        $publi->setTipo($request->request->get("tipo"));

        $carpeta = "img/" . $this->getUser()->getEmail() . "/" . str_replace(' ', '', $publi->getTitulo());
        $numPubli = 0;
        $auxCarpeta = $carpeta;
        while ($filesystem->exists($carpeta)) {
            $numPubli++;
            $auxCarpeta = $carpeta . $numPubli;
        }

        $carpeta = $auxCarpeta;
        $filesystem->mkdir($carpeta, 0777);

        for ($i = 1; $i <= $numFiles; $i++) {
            if ($_FILES["imgPubli" . $i]["size"] != 0) {
                $file = $_FILES["imgPubli" . $i];

                $fichero = "/" . str_replace(' ', '', $publi->getTitulo()) . $i . ".jpg";
                $ruta = $carpeta . $fichero;
                move_uploaded_file($file["tmp_name"], "/home/dwes/proyectoFinalDAW/public/" . $ruta);

                array_push($arrayImages, $ruta);
            }
        }

        $publi->setImagenes($arrayImages);

        $entityManager->persist($publi);
        return $entityManager->flush();
    }

    private function modificarPublicacion(Request $request)
    {
        $arrayImages = [];
        $numFiles = $request->request->get("numImg");
        $entityManager = $this->getDoctrine()->getManager();

        $publi = $this->obtenerPublicacion($request->request->get("idPubli"));

        $etiquetas = explode("/", $request->request->get("allEtiquetas"));
        $existEtiquetas = $publi->getEtiqueta();

        for ($i = 0; $i < count($existEtiquetas); $i++) {
            $entityManager->remove($existEtiquetas[$i]);
        }

        for ($i = 0; $i < count($etiquetas); $i++) {
            $etiqueta = new Etiquetas();
            $etiqueta->setEtiqueta($etiquetas[$i]);
            $entityManager->persist($etiqueta);

            $publi->addEtiquetum($etiqueta);
        }

        $publi->setTitulo($request->request->get("titulo"));
        if (strlen(str_replace(' ', '', $request->request->get("descripcion"))) == 0) {
            $publi->setDescripcion("Sin descripción");
        } else {
            $publi->setDescripcion($request->request->get("descripcion"));
        }

        $publi->setTipo($request->request->get("tipo"));

        $carpeta = "img/" . $publi->getUsuario()->getEmail() . "/" . explode("/", $publi->getImagenes()[0])[2];

        $aux = 1;
        for ($x = 0; $x < count($publi->getImagenes()); $x++) {
            if ($request->request->get("delete" . $x) == "on") {
                if ($numFiles == 0) {
                    $file = $publi->getImagenes()[$x];
                    unlink("/home/dwes/proyectoFinalDAW/public/" . $file);
                    $publi->setImagenes(array_diff($publi->getImagenes(), array($file)));
                } else {
                    for ($i = $aux; $i <= $numFiles; $i++) {
                        if ($_FILES["imgPubli" . $i]["size"] != 0) {
                            $file = $_FILES["imgPubli" . $i];

                            $ruta = $publi->getImagenes()[$x];
                            move_uploaded_file($file["tmp_name"], "/home/dwes/proyectoFinalDAW/public/" . $ruta);

                            $aux = $i + 1;
                            $i = $numFiles;
                        }
                    }
                }
            }
        }

        $arrayImages = $publi->getImagenes();

        for ($i = $aux; $i <= $numFiles; $i++) {
            if ($_FILES["imgPubli" . $i]["size"] != 0) {
                $file = $_FILES["imgPubli" . $i];

                $fichero = "/" . str_replace(' ', '', $publi->getTitulo()) . (count($publi->getImagenes()) + 1) . ".jpg";
                $ruta = $carpeta . $fichero;
                move_uploaded_file($file["tmp_name"], "/home/dwes/proyectoFinalDAW/public/" . $ruta);

                array_push($arrayImages, $ruta);
            }
        }

        $publi->setImagenes($arrayImages);
        return $entityManager->flush();
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

    private function aplicarFiltro(int $id, Filtros $filtro = null)
    {
        $publicaciones = [];

        if ($id < 0) {
            $publicaciones = $this->obtenerPublicaciones("DESC");
        } else {
            $auxPubli = $this->obtenerPublicaciones($filtro->getOrdenFecha());
            for ($i = 0; $i < count($auxPubli); $i++) {
                if ($filtro->getProvincia() == $auxPubli[$i]->getUsuario()->getProvincia() || $filtro->getProvincia() == null) {
                    if ($filtro->getTipo() == $auxPubli[$i]->getTipo() || $filtro->getTipo() == null) {
                        if ($filtro->getEtiqueta() != null) {
                            for ($x = 0; $x < count($auxPubli[$i]->getEtiqueta()); $x++) {
                                if ($filtro->getEtiqueta() == $auxPubli[$i]->getEtiqueta()[$x]) {
                                    $publicaciones[] = $auxPubli[$i];
                                }
                            }
                        } else {
                            $publicaciones[] = $auxPubli[$i];
                        }
                    }
                }
            }
        }


        return $publicaciones;
    }

    private function obtenerProvincias()
    {
        $repository = $this->getDoctrine()->getRepository(Provincias::class);

        return $repository->findAll();
    }
}
