<?php
// src/Service/ChatService.php
namespace App\Service;

use App\Entity\Chat;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Messages;


class MessageService extends AbstractController
{
    public function guardarMensaje(array $params)
    {
        $mensaje = new Messages();

        $mensaje->setMensajes($params["mensaje"]);

        $mensaje->setChat($this->getDoctrine()->getRepository(Chat::class)->findOneBy(["topic" => $params["topic"]]));
        $mensaje->setTimeEnvio(new \DateTime());

        $mensaje->setUsuario($this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $params["id"]]));

        $mensaje->setPublicacion($params["publi"]);
        $mensaje->setVisto(false);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($mensaje);

        $entityManager->flush();
    }
}
