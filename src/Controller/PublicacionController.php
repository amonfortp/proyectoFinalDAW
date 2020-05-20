<?php

namespace App\Controller;

use App\Entity\Publicacion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Mercure\CookieGenerator;
use App\Service\PublicacionService;

/**
 * @IsGranted("ROLE_USER")
 */
class PublicacionController extends AbstractController
{
    private $cookie;
    private $service;

    public function __construct(CookieGenerator $cookieGenerator, PublicacionService $service)
    {
        $this->cookie = $cookieGenerator;
        $this->service = $service;
    }

    /**
     * @Route("/publicaciones/{id}", name="publicaciones")
     */
    public function index(int $id = -1)
    {


        $filtros = $this->getUser()->getFiltros();
        if ($id > (count($filtros) - 1)) {
            $id = -1;
        }
        $this->service->activarFiltro($id);
        if ($id < 0) {
            $publicaciones = $this->service->aplicarFiltro($id);
        } else {
            $publicaciones = $this->service->aplicarFiltro($id, $filtros[$id]);
        }

        $response = $this->render('publicacion/publicaciones.html.twig', [
            'controller_name' => 'PublicacionController',
            'publicaciones' => $publicaciones,
            'navRed' => $this->service->comprobarChats(),
            'provincias' => $this->service->obtenerProvincias(),
            'idFiltro' => $id,
            'filtros' => $filtros
        ]);

        $response->headers->setCookie($this->cookie->generate());

        return $response;
    }

    /**
     * @Route("/publicacion/{id}", name="publicacion")
     */
    public function indexPublicacion(int $id = 0)
    {
        $publicacion = $this->service->obtenerPublicacion($id);

        if ($id == 0) {
            $response = $this->redirectToRoute('publicaciones', ['id' => 0]);
        } else {
            $response = $this->render('publicacion/publicacion.html.twig', [
                'controller_name' => 'PublicacionController',
                'publicacion' => $publicacion,
                'navRed' => $this->service->comprobarChats()
            ]);

            $response->headers->setCookie($this->cookie->generate());
        }



        return $response;
    }

    /**
     * @Route("/formPublicacion/{errorNum}", name="formPublicacion")
     */
    public function indexFormPublicaciones(int $errorNum = 0)
    {
        $error = $this->service->mensajeErrorCreacion($errorNum);

        $response = $this->render('publicacion/formPublicacion.html.twig', [
            'controller_name' => 'PublicacionController',
            'error' => $error,
            'navRed' => $this->service->comprobarChats()
        ]);

        $response->headers->setCookie($this->cookie->generate());

        return $response;
    }

    /**
     * @Route("/formPublicacionRes", name="formPublicacionRes", methods={"POST"})
     */
    public function indexFormPublicacionesRes(Request $request)
    {
        $validar = $this->service->validarCreacion($request);

        if ($validar != 0) {
            return $this->redirectToRoute('formPublicacion', ['errorNum' => $validar]);
        } else {
            $this->service->crearPublicacion($request);
            return $this->redirectToRoute('perfil', ['id' => $this->getUser()->getId()]);
        }
    }

    /**
     * @Route("/modifPublicacion/{datos}", name="modifPublicacion")
     */
    public function indexModifPublicacion(string $datos)
    {
        $id = (int) explode("-", $datos)[0];
        $errorNum = (int) explode("-", $datos)[1];
        $publicacion = $this->service->obtenerPublicacion($id);
        $error = $this->service->mensajeErrorCreacion($errorNum);

        $response = $this->service->render('publicacion/modifPublicacion.html.twig', [
            'controller_name' => 'PublicacionController',
            'error' => $error,
            'publicacion' => $publicacion,
            'navRed' => $this->service->comprobarChats()
        ]);

        $response->headers->setCookie($this->cookie->generate());

        return $response;
    }

    /**
     * @Route("/modifPublicacionRes", name="modifPublicacionRes", methods={"POST"})
     */
    public function modifPublicacionRes(Request $request)
    {
        $validar = $this->service->validarModificacion($request);
        $datos = $request->request->get("idPubli") . "-" . $validar;
        if ($validar != 0) {
            return $this->redirectToRoute('modifPublicacion', ['datos' => $datos]);
        } else {
            $this->service->modificarPublicacion($request);
            return $this->redirectToRoute('perfil', ['id' => $this->getUser()->getId()]);
        }
    }

    /**
     * @Route("/eliminarPubli/{id}", name="eliminarPubli")
     */
    public function eliminarPublicacion(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Publicacion::class);
        $publicacion = $repository->findOneBy(['id' => $id]);
        $etiquetas = $publicacion->getEtiqueta();

        $this->service->deleteDir(__DIR__ . "/../../public/img/" . $publicacion->getUsuario()->getEmail() . "/" . explode("/", $publicacion->getImagenes()[0])[2]);

        $entityManager->remove($publicacion);
        for ($i = 0; $i < count($etiquetas); $i++) {
            $entityManager->remove($etiquetas[$i]);
        }
        $entityManager->flush();

        return $this->redirectToRoute('perfil', ['id' => $this->getUser()->getId()]);
    }
}
