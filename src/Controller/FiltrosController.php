<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FiltrosService;

/**
 * @IsGranted("ROLE_USER")
 */
class FiltrosController extends AbstractController
{
    private $service;

    public function __construct(FiltrosService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/filtros", name="filtros", methods={"POST"})
     */
    public function index(Request $request)
    {
        $id = (int) $request->request->get("idFiltro");

        $filtros = $this->getUser()->getFiltros();
        if ($request->request->get("accion") == 0 && $request->request->get("idFiltro") >= 0) {
            $this->service->eliminarFiltro($filtros[$id]->getId());
        } else if ($request->request->get("accion") == 1) {
            if (strlen($request->request->get("titulo")) > 25) {
                return $this->redirectToRoute('ajustes', ['errorNum' => 8]);
            } else {
                $this->service->guardarFiltro($request);
            }
        }

        return $this->redirectToRoute('ajustes');
    }
}
