<?php
// src/Service/ChatService.php
namespace App\Service;

use App\Entity\Chat;
use App\Entity\User;
use App\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatService extends AbstractController
{
    public function ordenarId(string $id1, string $id2)
    {
        if ((int) $id1 > (int) $id2) {
            return $id2 . "" . $id1;
        } else {
            return $id1 . "" . $id2;
        }
    }

    public function guardarChat(array $params)
    {
        $chat = new Chat();
        $chat->setUsuario1($this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $params["usu1"]]));

        $chat->setUsuario2($this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $params["usu2"]]));

        $chat->setTopic($params["topic"]);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($chat);


        $entityManager->flush();
    }

    public function actualizarEstadoMensajes(array $mensajes)
    {
        $entityManager = $this->getDoctrine()->getManager();

        for ($i = 0; $i < count($mensajes); $i++) {
            if ($mensajes[$i]->getUsuario() != $this->getUser()) {
                $mensajes[$i]->setVisto(true);
            }
        }

        $entityManager->flush();
    }

    public function comprobarChats()
    {
        $allChats = $this->getDoctrine()->getRepository(Chat::class)->findAll();

        $aviso = 0;

        for ($i = 0; $i < count($allChats); $i++) {
            $mensaje = $this->getDoctrine()->getRepository(Messages::class)->findOneBy([
                "chat" => $allChats[$i],
                "visto" => false
            ]);
            if ($mensaje != null) {
                if ($mensaje->getUsuario()->getId() != $this->getUser()->getId() && ($allChats[$i]->getUsuario1()->getId() == $this->getUser()->getId() || $allChats[$i]->getUsuario2()->getId() == $this->getUser()->getId())) {
                    $aviso = 1;
                    break;
                }
            }
        }

        return $aviso;
    }
}
