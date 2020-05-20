<?php

namespace App\Controller;

use App\Entity\Etiquetas;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Filtros;
use App\Entity\Provincias;

/**
 * @IsGranted("ROLE_USER")
 */
class FiltrosController extends AbstractController
{
    /**
     * @Route("/filtros", name="filtros", methods={"POST"})
     */
    public function index(Request $request)
    {
        $id = (int) $request->request->get("idFiltro");

        $filtros = $this->getUser()->getFiltros();
        if ($request->request->get("accion") == 2 && $id >= 0) {
            $this->eliminarFiltro($filtros[$id]->getId());
            return $this->redirectToRoute('publicaciones', ['id' => -1]);
        } else if ($request->request->get("accion") == 1) {
            if (strlen($request->request->get("titulo")) > 25) {
                return $this->redirectToRoute('ajustes', ['errorNum' => 8]);
            } else {
                $this->guardarFiltro($request);
                return $this->redirectToRoute('publicaciones', ['id' => count($filtros) - 1]);
            }
        } else {
            $this->activarFiltro($request);
            return $this->redirectToRoute('publicaciones', ['id' => $id]);
        }
    }

    private function guardarFiltro(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $filtro = new Filtros();
        $repositoryProvincia = $this->getDoctrine()->getRepository(Provincias::class);
        $repositoryEtiqueta = $this->getDoctrine()->getRepository(Etiquetas::class);

        $filtro->setOrdenFecha($request->request->get("orden"));
        $filtro->setProvincia($repositoryProvincia->findOneBy(["id" => $request->request->get("provincia")]));
        $filtro->setUsuarioProp($this->getUser());
        $filtro->setEtiqueta($repositoryEtiqueta->findOneBy(["etiqueta" => $request->request->get("etiqueta")]));

        if ($request->request->get("tipo") == "0") {
            $filtro->setTipo(null);
        } else {
            $filtro->setTipo($request->request->get("tipo"));
        }

        if (strlen(str_replace(' ', '', $request->request->get("titulo"))) == 0) {
            $filtro->setTitulo("Sin titulo");
        } else {
            $filtro->setTitulo($request->request->get("titulo"));
        }

        $filtro->setActivo(true);

        $entityManager->persist($filtro);
        $entityManager->flush();
    }

    private function eliminarFiltro($id)
    {
        $entityManager = $this->getDoctrine()->getManager();



        $repository = $this->getDoctrine()->getRepository(Filtros::class);
        $filtro = $repository->findOneBy(["id" => $id]);
        $entityManager->remove($filtro);
        $entityManager->flush();
    }

    private function activarFiltro(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $filtros = $this->getUser()->getFiltros();

        $filtroActivo = $this->getDoctrine()->getRepository(Filtros::class)->findOneBy(["usuarioProp" => $this->getUser(), "activo" => true]);
        if ($filtroActivo != null) {
            $filtroActivo->setActivo(false);
        }

        if ((int) $request->request->get("idFiltro") >= 0) {
            $filtros[$request->request->get("idFiltro")]->setActivo(true);
        }

        $entityManager->flush();
    }
}
