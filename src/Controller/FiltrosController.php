<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class FiltrosController extends AbstractController
{
    /**
     * @Route("/filtros", name="filtros", methods={"POST"})
     */
    public function index()
    {
        return $this->redirectToRoute('publicaciones', ['id' => 0]);
    }
}
