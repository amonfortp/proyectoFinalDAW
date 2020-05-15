<?php

namespace App\Controller;

use App\Entity\Chat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/** 
 * @IsGranted("ROLE_USER") 
 */
class ListaChatsController extends AbstractController
{
    /**
     * @Route("/listaChats", name="listaChats")
     */
    public function mostrarChats()
    {
        $chats = $this->getDoctrine()->getRepository(Chat::class)->findAll();

        return $this->render('lista_chats/index.html.twig', [
            'chats' => $chats
        ]);
    }
}
