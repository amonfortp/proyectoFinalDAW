<?php

namespace App\Controller;

use App\Service\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;

class RegisterController extends AbstractController
{
    private $service;

    public function __construct(RegisterService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/register/{errorNum}", name="register")
     */
    public function index(int $errorNum = 0)
    {
        $error = $this->service->mensajeError($errorNum);
        $provincias = $this->service->obtenerProvincias();

        return $this->render('register/register.html.twig', [
            'controller_name' => 'RegisterController', 'error' => $error, 'provincias' => $provincias
        ]);
    }

    /**
     * @Route("/verificar", name="verificar", methods={"POST"})
     */
    public function verificar(Request $request)
    {
        $error = $this->service->validar($request);
        $filesystem = new Filesystem();


        if ($error != 0) {
            return $this->redirectToRoute('register', ['errorNum' => $error]);
        } else {
            $this->service->registrar($request);
            /*
            if (!$filesystem->exists('/home/dwes/proyectoFinalDAW/public/img/' . $request->request->get("email"))) {
                $filesystem->mkdir('/home/dwes/proyectoFinalDAW/public/img/' . $request->request->get("email"), 0777);
                $filesystem->mkdir('/home/dwes/proyectoFinalDAW/public/img/' . $request->request->get("email") . '/perfil', 0777);
            }*/
            if (!$filesystem->exists(__DIR__ . '/../../public/img/' . $request->request->get("email"))) {
                $filesystem->mkdir(__DIR__ . '/../../public/img/' . $request->request->get("email"), 0777);
                $filesystem->mkdir(__DIR__ . '/../../public/img/' . $request->request->get("email") . '/perfil', 0777);
            }

            return $this->redirectToRoute('app_login', ['exito' => '*']);
        }
    }
}
