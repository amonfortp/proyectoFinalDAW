<?php
// src/Service/ChatService.php
namespace App\Service;

use App\Entity\Etiquetas;
use App\Entity\Publicacion;
use App\Entity\Provincias;
use App\Entity\Chat;
use App\Entity\Filtros;
use App\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;

class PublicacionService extends AbstractController
{
    public static function deleteDir($dirPath)
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

    public function obtenerPublicaciones(String $orden = 'DESC')
    {
        $repository = $this->getDoctrine()->getRepository(Publicacion::class);
        $publicaciones = $repository->findBy(['activo' => true], ['fechaPublicacion' => $orden]);

        return $publicaciones;
    }

    public function obtenerPublicacion(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Publicacion::class);
        $publicacion = $repository->findOneBy(['id' => $id]);

        return $publicacion;
    }

    public function mensajeErrorCreacion(int $error)
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

    public function validarCreacion(Request $request): int
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

    public function validarModificacion(Request $request): int
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

    public function crearPublicacion(Request $request)
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
        while ($filesystem->exists($auxCarpeta)) {
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
                //move_uploaded_file($file["tmp_name"], "/home/dwes/proyectoFinalDAW/public/" . $ruta);
                move_uploaded_file($file["tmp_name"], __DIR__ . '/../../public/' . $ruta);
                array_push($arrayImages, $ruta);
            }
        }

        $publi->setImagenes($arrayImages);

        $entityManager->persist($publi);
        return $entityManager->flush();
    }

    public function modificarPublicacion(Request $request)
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
                    unlink(__DIR__ . '/../../public/' . $file);
                    $publi->setImagenes(array_diff($publi->getImagenes(), array($file)));
                } else {
                    for ($i = $aux; $i <= $numFiles; $i++) {
                        if ($_FILES["imgPubli" . $i]["size"] != 0) {
                            $file = $_FILES["imgPubli" . $i];

                            $ruta = $publi->getImagenes()[$x];
                            move_uploaded_file($file["tmp_name"], __DIR__ . '/../../public/' . $ruta);

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
                move_uploaded_file($file["tmp_name"], __DIR__ . '/../../public/' . $ruta);

                array_push($arrayImages, $ruta);
            }
        }

        $publi->setImagenes($arrayImages);
        return $entityManager->flush();
    }

    public function comprobarChats()
    {
        $allChats = $this->getDoctrine()->getRepository(Chat::class)->findAll();

        $aviso = 0;

        for ($i = 0; $i < count($allChats); $i++) {
            $mensaje = $this->getDoctrine()->getRepository(Messages::class)->findOneBy([
                "chat" => $allChats[$i],
                "visto" => false
            ]);
            if ($mensaje != null) {
                if ($mensaje->getUsuario()->getId() != $this->getUser()->getId() && ($allChats[$i]->getUsuario1()->getId() == $this->getUser()->getId() || $allChats[$i]->getUsuario2()->getId() == $this->getUser()->getId())) {
                    $aviso = 1;
                    break;
                }
            }
        }

        return $aviso;
    }

    public function aplicarFiltro(int $id, Filtros $filtro = null)
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
                            return [];
                            $publicaciones[] = $auxPubli[$i];
                        }
                    }
                }
            }
        }


        return $publicaciones;
    }

    public function activarFiltro($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $filtros = $this->getUser()->getFiltros();

        $filtroActivo = $this->getDoctrine()->getRepository(Filtros::class)->findOneBy(["usuarioProp" => $this->getUser(), "activo" => true]);
        if ($filtroActivo != null) {
            $filtroActivo->setActivo(false);
        }

        if ((int) $id >= 0) {
            $filtros[$id]->setActivo(true);
        }

        $entityManager->flush();
    }

    public function obtenerProvincias()
    {
        $repository = $this->getDoctrine()->getRepository(Provincias::class);

        return $repository->findAll();
    }
}
