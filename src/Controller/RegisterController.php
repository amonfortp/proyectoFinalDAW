<?php

namespace App\Controller;

use App\Entity\Provincias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index()
    {
        $error = null;
        $provincias = $this->obtenerProvincias();

        return $this->render('register/register.html.twig', [
            'controller_name' => 'RegisterController', 'error' => $error, 'provincias' => $provincias
        ]);
    }

    /**
     * @Route("/verificar", name="verificar" , methods={"POST"})
     */
    public function verificar(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $this->validar($request);
        $provincias = $this->obtenerProvincias();


        if (!$error == "") {
            return $this->render('register/register.html.twig', [
                'controller_name' => 'RegisterController', 'error' => $error, 'provincias' => $provincias
            ]);
        } else {
            $lastUsername = $authenticationUtils->getLastUsername();
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => null, 'exito' => true]);
        }
    }

    private function validar(Request $request): string
    {
        $error = "";
        $nombre = $request->request->get("nombre");
        $email = $request->request->get("email");
        $pass = $request->request->get("contrasena");
        $confPass = $request->request->get("confContrasena");


        return $error;
    }

    private function obtenerProvincias()
    {
        $repository = $this->getDoctrine()->getRepository(Provincias::class);

        return $repository->findAll();
    }
}
