<?php

namespace App\Controller;

use App\Entity\Provincias;
use App\Entity\Chat;
use App\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Mercure\CookieGenerator;

/**
 * @IsGranted("ROLE_USER")
 */
class AjustesController extends AbstractController
{
    private $passwordEncoder;
    private $cookie;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, CookieGenerator $cookieGenerator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->cookie = $cookieGenerator;
    }

    /**
     * @Route("/ajustes/{errorNum}", name="ajustes")
     */
    public function index(int $errorNum = 0)
    {
        $provincias = $this->obtenerProvincias();
        $error = $this->mensajeErrorPerfil($errorNum);
        $filtros = $this->getUser()->getFiltros();
        $idFiltro = -1;
        if ($errorNum == 8) {
            $errorFiltro = 'El titulo no puede superar los 25 caracteres';
        } else {
            $errorFiltro = null;
        }

        for ($i = 0; $i < count($filtros); $i++) {
            if ($filtros[$i]->getActivo() == true) {
                $idFiltro = $i;
            }
        }

        $response = $this->render('ajustes/ajustes.html.twig', [
            'controller_name' => 'AjustesController',
            'provincias' => $provincias,
            'error' => $error,
            'navRed' => $this->comprobarChats(),
            'errorFiltro' => $errorFiltro,
            'idFiltro' => $idFiltro,
            'filtros' => $filtros
        ]);

        $response->headers->setCookie($this->cookie->generate());

        return $response;
    }

    /**
     * @Route("/modificarPerfil", name="modificarPerfil", methods={"POST"})
     */
    public function modificarPerfil(Request $request)
    {
        $validar = $this->validar($request);

        if ($validar != 0) {
            return $this->redirectToRoute('ajustes', ['errorNum' => $validar]);
        } else {
            $this->modificar($request);
            return $this->redirectToRoute('perfil', ['id' => $this->getUser()->getId()]);
        }
    }

    private function obtenerProvincias()
    {
        $repository = $this->getDoctrine()->getRepository(Provincias::class);

        return $repository->findAll();
    }

    private function mensajeErrorPerfil(int $error)
    {
        $mensaje = null;

        if ($error == 1) {
            $mensaje = "El nombre debe tener entre 3 y 25 caracteres";
        } else if ($error == 4) {
            $mensaje = "La contraseña debe tener almenos entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula y al menos una mayúscula.
            Puede tener otros símbolos.";
        } else if ($error == 5) {
            $mensaje = "La contraseña y la confirmación deben ser iguales";
        } else if ($error == 6) {
            $mensaje = "Tu contraseña actual es incorrecta o esta en blanco";
        } else if ($error == 7) {
            $mensaje = "Se permiten archivos .jpg, .png. y de 200 kb como máximo";
        }

        return $mensaje;
    }

    private function validarPassword($password)
    {
        return preg_match("^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$^", $password);
    }

    private function validar(Request $request): int
    {
        $error = 0;
        $nombre = $request->request->get("nombre");
        $passActual = $request->request->get("contrasenaActual");
        $pass = $request->request->get("contrasena");
        $confPass = $request->request->get("confContrasena");
        $file = $_FILES["imgPerfil"];


        if (!$this->passwordEncoder->isPasswordValid($this->getUser(), $passActual)) {
            $error = 6;
        } elseif ((strlen(str_replace(' ', '', $nombre)) > 25 || strlen($nombre) < 3) && strlen(str_replace(' ', '', $nombre)) > 0) {
            $error = 1;
        } else if (!$this->validarPassword($pass) && str_replace(' ', '', $pass) != "") {
            $error = 4;
        } else if (strcmp($pass, $confPass) != 0) {
            $error = 5;
        } else if ($file['size'] > 0) {
            if (!((strpos($file['type'], "jpeg") || strpos($file['type'], "jpg") || strpos($file['type'], "png")) && ($file['size'] < 2000000))) {
                $error = 7;
            }
        }
        return $error;
    }

    private function modificar(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $file = $_FILES["imgPerfil"];
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["email" => $this->getUser()->getEmail()]);

        if (str_replace(' ', '', $request->request->get("nombre")) != "") {
            $user->setNickName($request->request->get("nombre"));
        }

        if (str_replace(' ', '', $request->request->get("contrasena")) != "") {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $request->request->get("contrasena")));
        }

        if ($request->request->get("provincias") != 0) {
            $repository = $this->getDoctrine()->getRepository(Provincias::class);
            $user->setProvincia($repository->findOneBy(["id" => $request->request->get("provincias")]));
        } else {
            $user->setProvincia(null);
        }

        if ($file['size'] > 0) {
            $ruta = "img/" . $user->getEmail() . "/perfil/fotoPerfil.jpg";
            move_uploaded_file($file["tmp_name"], "/home/dwes/proyectoFinal/public/" . $ruta);
            $user->setImagenPerfil($ruta);
        }

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
}
