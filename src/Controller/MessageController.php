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
        $topic = 'http://chat.monbarter/'  .  $this->service->ordenarId($this->getUser()->getId(), $request->request->get('usuario2id'));

        $update = new Update($topic, json_encode([
            'user' => $this->getUser()->getEmail(),
            'message' => $mensaje,
            'time' => $time
        ]));
        $bus->dispatch($update);

        $params = [
            "mensaje" => $mensaje,
            "topic" => $topic,
            "id" => $this->getUser()->getId()
        ];

        $this->guardarMensaje($params);

        return $this->redirectToRoute('chat', [
            'id' => $request->request->get('usu2Id'),
        ]);
    }

    private function guardarMensaje(array $params)
    {
        $mensaje = new Messages();

        $mensaje->setMensajes($params["mensaje"]);

        $mensaje->setChat($this->getDoctrine()->getRepository(Chat::class)->findOneBy(["Topic" => $params["topic"]]));
        $mensaje->setTimeEnvio(new \DateTime());

        $mensaje->setUsuario($this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $params["id"]]));

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($mensaje);

        try {
            $entityManager->flush();
            return "exito";
        } catch (\Exception $e) {
            return $e;
        }
    }
}
