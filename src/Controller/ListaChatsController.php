<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Mercure\CookieGenerator;
use Symfony\Component\Mime\Message;

/** 
 * @IsGranted("ROLE_USER") 
 */
class ListaChatsController extends AbstractController
{
    private $cookie;

    public function __construct(CookieGenerator $cookieGenerator)
    {
        $this->cookie = $cookieGenerator;
    }

    /**
     * @Route("/listaChats", name="listaChats")
     */
    public function mostrarChats()
    {
        $allChats = $this->getDoctrine()->getRepository(Chat::class)->findAll();

        $chats = [];
        $chatAvisar = [];

        for ($i = 0; $i < count($allChats); $i++) {
            if ($allChats[$i]->getUsuario1()->getId() == $this->getUser()->getId()) {
                $chats[] = $allChats[$i];
                if ($this->getDoctrine()->getRepository(Messages::class)->findOneBy([
                    "chat" => $allChats[$i],
                    "visto" => false,
                    "usuario" => $allChats[$i]->getUsuario2()
                ])) {
                    $chatAvisar[] = $allChats[$i];
                }
            } else if ($allChats[$i]->getUsuario2()->getId() == $this->getUser()->getId()) {
                $chats[] = $allChats[$i];
                if ($this->getDoctrine()->getRepository(Messages::class)->findOneBy([
                    "chat" => $allChats[$i],
                    "visto" => false,
                    "usuario" => $allChats[$i]->getUsuario1()
                ])) {
                    $chatAvisar[] = $allChats[$i];
                }
            }
        }



        $response =  $this->render('lista_chats/index.html.twig', [
            'chats' => $chats,
            'notificar' => $chatAvisar,
            'navRed' => 0
        ]);

        $response->headers->setCookie($this->cookie->generate());

        return $response;
    }
}
