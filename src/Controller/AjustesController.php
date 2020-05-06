<?php

namespace App\Controller;

use App\Entity\Provincias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @IsGranted("ROLE_USER")
 */
class AjustesController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/ajustes/{errorNum}", name="ajustes")
     */
    public function index(int $errorNum = 0)
    {
        $provincias = $this->obtenerProvincias();
        $error = $this->mensajeErrorPerfil($errorNum);

        return $this->render('ajustes/ajustes.html.twig', [
            'controller_name' => 'AjustesController',
            'provincias' => $provincias,
            'error' => $error
        ]);
    }

    /**
     * @Route("/modificarPerfil", name="modificarPerfil", methods={"POST"})
     */
    public function modificarPerfil(Request $request)
    {
        $validar = $this->validar($request);
        $filesystem = new Filesystem();

        if ($validar == 0) {
            $this->modificar($request);
        }

        return $this->redirectToRoute('ajustes', ['errorNum' => $validar]);
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
        } else if ($error == 2) {
            $mensaje = "El formato de correo electrónico (email) es erroneo";
        } else if ($error == 3) {
            $mensaje = "Este correo ya esta en uso";
        } else if ($error == 4) {
            $mensaje = "La contraseña debe tener almenos entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula y al menos una mayúscula.
            Puede tener otros símbolos.";
        } else if ($error == 5) {
            $mensaje = "La contraseña y la confirmación deben ser iguales";
        } else if ($error == 6) {
            $mensaje = "Tu contraseña actual es incorrecta o esta en blanco";
        } else if ($error == 7) {
            $mensaje = "Solo se permiten imagenes, como jpg o png";
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
        $email = $request->request->get("email");
        $passActual = $request->request->get("contrasenaActual");
        $pass = $request->request->get("contrasena");
        $confPass = $request->request->get("confContrasena");
        $file = $request->request->get("imgPerfil");
        $repository = $this->getDoctrine()->getRepository(User::class);


        if (!$this->passwordEncoder->isPasswordValid($this->getUser(), $passActual)) {
            $error = 6;
        } elseif (strlen(str_replace(' ', '', $nombre)) > 25 || strlen(str_replace(' ', '', $nombre)) < 3) {
            $error = 1;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 2;
        } else if ($repository->findOneBy(["email" => $email]) && $repository->findOneBy(["email" => $email]) != $this->getUser()) {
            $error = 3;
        } else if (!$this->validarPassword($pass)) {
            $error = 4;
        } else if (strcmp($pass, $confPass) != 0) {
            $error = 5;
        } else  if ($file && !exif_imagetype($file)) {
            $error = 7;
        }

        return $error;
    }

    private function modificar(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["email" => $this->getUser()->getEmail()]);

        if (str_replace(' ', '', $request->request->get("email")) != "") {
            $user->setEmail($request->request->get("email"));
        }

        if (str_replace(' ', '', $request->request->get("nombre")) != "") {
            $user->setNickName($request->request->get("nombre"));
        }

        if (str_replace(' ', '', $request->request->get("contrasena")) != "") {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $request->request->get("contrasena")));
        }

        if ($request->request->get("provincias") != 0) {
            $repository = $this->getDoctrine()->getRepository(Provincias::class);
            $user->setProvincia($repository->findOneBy(["id" => $request->request->get("provincias")]));
        }

        return $entityManager->flush();
    }
}
