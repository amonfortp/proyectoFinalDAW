<?php
// src/Service/ChatService.php
namespace App\Service;

use App\Entity\User;
use App\Entity\Provincias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegisterService extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface  $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function mensajeError(int $error)
    {
        $mensaje = null;

        if ($error == 1) {
            $mensaje = "El nombre debe tener entre 3 y 25 caracteres";
        } else if ($error == 2) {
            $mensaje = "El formato de correo electrónico (email) es erroneo";
        } else if ($error == 3) {
            $mensaje = "Este correo ya esta en uso";
        } else if ($error == 4) {
            $mensaje = "La contraseña debe tener al menos entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula y al menos una mayúscula.
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

    public function validar(Request $request): int
    {
        $error = 0;
        $nombre = $request->request->get("nombre");
        $email = $request->request->get("email");
        $pass = $request->request->get("contrasena");
        $confPass = $request->request->get("confContrasena");
        $checkBox = $request->request->get("condiciones");
        $repository = $this->getDoctrine()->getRepository(User::class);

        if (strlen(str_replace(' ', '', $nombre)) > 25 || strlen(str_replace(' ', '', $nombre)) < 3) {
            $error = 1;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 2;
        } else if ($repository->findOneBy(["email" => $email])) {
            $error = 3;
        } else if (!$this->validarPassword($pass)) {
            $error = 4;
        } else if (strcmp($pass, $confPass) != 0) {
            $error = 5;
        } else if (str_replace(' ', '', $nombre) == "" || str_replace(' ', '', $email) == "" || str_replace(' ', '', $pass) == "") {
            $error = 6;
        } else if (!$checkBox) {
            $error = 7;
        }

        return $error;
    }

    public function registrar(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setEmail($request->request->get("email"));
        $user->setNickName($request->request->get("nombre"));
        $user->setPassword($this->passwordEncoder->encodePassword($user, $request->request->get("contrasena")));
        if ($request->request->get("provincias") != 0) {
            $repository = $this->getDoctrine()->getRepository(Provincias::class);
            $user->setProvincia($repository->findOneBy(["id" => $request->request->get("provincias")]));
        }
        $entityManager->persist($user);
        return $entityManager->flush();
    }

    public function validarPassword($password)
    {
        return preg_match("^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$^", $password);
    }

    public function obtenerProvincias()
    {
        $repository = $this->getDoctrine()->getRepository(Provincias::class);

        return $repository->findAll();
    }
}
