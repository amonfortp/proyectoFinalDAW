<?php
// src/Controller/ChatController.php
namespace App\Controller;

use App\Entity\Chat;
use App\Entity\User;
use App\Entity\Publicacion;
use App\Mercure\CookieGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\ChatService;

/** 
 * @IsGranted("ROLE_USER") 
 */
final class ChatController extends AbstractController
{
    //Diferencia si el controlador esta iniciado o no
    private $inicio = false;

    private $service;

    /**
     * @Route("/chat/{id}", name="chat")
     * 
     * Funcion utilizada para crear u obtener el chat, ademas de sus posibles mensajes y cargarl la plantilla correspondiente
     * @param cookieGenerator -> Objeto para generar una cookie con clave JWT
     * @param id -> ID del usuario pasado por URL
     * @param service -> Servicio utilizado para usar sus propias funciones
     */
    public function __invoke(CookieGenerator $cookieGenerator, String $id, ChatService $service): Response
    {
        $chat = null;
        $this->service = $service;
        $respuesta = "";
        $mensajes = null;
        $user = $this->getUser();
        if (explode("_", $id)[1] != 0) {
            if (!$this->getDoctrine()->getRepository(Chat::class)->findOneBy([
                "usuario1" => (int) explode("_", $id)[0],
                "usuario2" => $user->getId()
            ]) && !$this->getDoctrine()->getRepository(Chat::class)->findOneBy([
                "usuario2" => (int) explode("_", $id)[0],
                "usuario1" => $user->getId()
            ])) {
                $respuesta = $this->guardarChat([
                    "usu1" => $user->getId(),
                    "usu2" => (int) explode("_", $id)[0],
                    "topic" => 'http://chat.monbarter/' .  $this->service->ordenarId($user->getId(), (int) explode("_", $id)[0])

                ]);
            } else {
                if (!$this->inicio) {
                    $chat = $this->getDoctrine()->getRepository(Chat::class)->findOneBy([
                        "Topic" => 'http://chat.monbarter/' .  $this->service->ordenarId($user->getId(), $id)
                    ]);

                    $mensajes = $chat->getMessages();

                    $this->inicio = true;
                }
            }
        }


        if ($chat != null) {
            $publicacion = $this->getDoctrine()->getRepository(Publicacion::class)->findOneBy(["id" => explode("_", $id)[1] != 0]);

            $usu = $this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $id]);
            if ($respuesta == "") {
                $respuesta = $this->render('chat/index.html.twig', [
                    "id" => $id,
                    "email" => $usu->getEmail(),
                    "mensajes" => $mensajes,
                    "publicacion" => $publicacion
                ]);
            }

            $response = $respuesta;

            $response->headers->setCookie($cookieGenerator->generate());
        } else {
            $response = $this->render('publicaciones/index.html.twig', [
                "id" => $id,
                "email" => $usu->getEmail(),
                "mensajes" => $mensajes
            ]);
        }

        return $response;
    }

    private function guardarChat(array $params)
    {
        $chat = new Chat();
        $chat->setUsuario1($this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $params["usu1"]]));

        $chat->setUsuario2($this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $params["usu2"]]));

        $chat->setTopic($params["topic"]);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($chat);


        $entityManager->flush();
    }
}
