<?php
// src/Service/ChatService.php
namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Entity\Chat;
use App\Entity\Messages;


class PerfilService extends AbstractController
{
    public function obtenerUsuario(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $usuario = $repository->findOneBy(['id' => $id]);

        return $usuario;
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
