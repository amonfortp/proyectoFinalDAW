<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Mercure\CookieGenerator;
use App\Service\AjustesService;

/**
 * @IsGranted("ROLE_USER")
 */
class AjustesController extends AbstractController
{
    private $cookie;
    private $service;

    public function __construct(CookieGenerator $cookieGenerator, AjustesService $service)
    {
        $this->cookie = $cookieGenerator;
        $this->service = $service;
    }

    /**
     * @Route("/ajustes/{errorNum}", name="ajustes")
     */
    public function index(int $errorNum = 0)
    {
        $provincias = $this->service->obtenerProvincias();
        $error = $this->service->mensajeErrorPerfil($errorNum);
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
            'navRed' => $this->service->comprobarChats(),
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
        $validar = $this->service->validar($request);

        if ($validar != 0) {
            return $this->redirectToRoute('ajustes', ['errorNum' => $validar]);
        } else {
            $this->service->modificar($request);
            return $this->redirectToRoute('perfil', ['id' => $this->getUser()->getId()]);
        }
    }
}
