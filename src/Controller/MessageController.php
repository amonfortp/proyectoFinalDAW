<?php
// src/Controller/MessageController.php
namespace App\Controller;

use App\Entity\Chat;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ChatService;
use App\Entity\Messages;
use App\Entity\Publicacion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/** 
 * @IsGranted("ROLE_USER") 
 */
final class MessageController extends AbstractController
{
    private $service;

    /**
     * @Route("/message", name="sendMessage", methods={"POST"})
     */
    public function __invoke(MessageBusInterface $bus, Request $request, ChatService $service): RedirectResponse
    {
        $this->service = $service;
        $mensaje = $request->request->get('message');
        $time = $request->request->get('time');
        $numPubli = $request->request->get('numPubli');
        $topicUsuario = 'http://monbarter/' .  $request->request->get('usuario2id');
        $topic = 'http://chat.monbarter/'  .  $this->service->ordenarId($this->getUser()->getId(), $request->request->get('usuario2id'));

        if ($numPubli == 0) {
            $publi = null;
        } else {
            $publi = $this->getDoctrine()->getRepository(Publicacion::class)->findOneBy(["id" => $request->request->get("publi1")]);
        }

        $updateUsuario = new Update($topicUsuario, json_encode([
            'id' => $request->request->get('usuario2id')
        ]));

        $updateGeneral = new Update($topic, json_encode([
            'user' => $this->getUser()->getnickName(),
            'id' => $this->getUser()->getId(),
            'message' => $mensaje,
            'time' => $time,
            'numPubli' => $numPubli
        ]));

        $bus->dispatch($updateUsuario);
        $bus->dispatch($updateGeneral);

        $params = [
            "publi" => $publi,
            "mensaje" => $mensaje,
            "topic" => $topic,
            "id" => $this->getUser()->getId()
        ];

        $this->guardarMensaje($params);

        return $this->redirectToRoute('chat', [
            'id' => $request->request->get('usuario2id') . "_0",
        ]);
    }

    private function guardarMensaje(array $params)
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
