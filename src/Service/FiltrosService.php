<?php
// src/Service/ChatService.php
namespace App\Service;

use App\Entity\Etiquetas;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Filtros;
use App\Entity\Provincias;


class FiltrosService extends AbstractController
{
    public function guardarFiltro(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $filtro = new Filtros();
        $repositoryProvincia = $this->getDoctrine()->getRepository(Provincias::class);
        $repositoryEtiqueta = $this->getDoctrine()->getRepository(Etiquetas::class);

        $filtro->setOrdenFecha($request->request->get("orden"));
        $filtro->setProvincia($repositoryProvincia->findOneBy(["id" => $request->request->get("provincia")]));
        $filtro->setUsuarioProp($this->getUser());

        $eti = $repositoryEtiqueta->findOneBy(["etiqueta" => $request->request->get("etiqueta")]);

        if ($eti == null && $request->request->get("etiqueta") != "") {
            $aux = new Etiquetas();
            $aux->setEtiqueta($request->request->get("etiqueta"));
            $entityManager->persist($aux);
            $filtro->setEtiqueta($aux);
        } else if ($eti != null) {
            $filtro->setEtiqueta($eti);
        } else {
            $filtro->setEtiqueta(null);
        }



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

    public function eliminarFiltro($id)
    {
        $entityManager = $this->getDoctrine()->getManager();



        $repository = $this->getDoctrine()->getRepository(Filtros::class);
        $filtro = $repository->findOneBy(["id" => $id]);
        $entityManager->remove($filtro);
        $entityManager->flush();
    }
}
