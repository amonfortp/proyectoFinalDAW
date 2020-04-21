<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class PublicacionController extends AbstractController
{
    /**
     * @Route("/publicacion", name="publicacion")
     */
    public function index()
    {
        return $this->render('publicacion/publicacion.html.twig', [
            'controller_name' => 'PublicacionController',
        ]);
    }
}
