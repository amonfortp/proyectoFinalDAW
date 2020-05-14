<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/** 
 * @IsGranted("ROLE_USER") 
 */
class ListaChatController extends AbstractController
{
    /**
     * @Route("/listaChats", name="listaChats")
     */
    public function mostrarUsuarios()
    {
        $chats = [];

        array_push($chats, $this->getDoctrine()->getRepository(User::class)->findBy(["usuario1" => $this->getUser()]));
        array_push($chats, $this->getDoctrine()->getRepository(User::class)->findBy(["usuario2" => $this->getUser()]));

        return $this->render('lista_chats/index.html.twig', [
            'chats' => $chats
        ]);
    }
}
