<?php
// src/Controller/MessageController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ChatService;
use App\Entity\Publicacion;
use App\Service\MessageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/** 
 * @IsGranted("ROLE_USER") 
 */
final class MessageController extends AbstractController
{
    private $serviceC;
    private $serviceM;

    /**
     * @Route("/message", name="sendMessage", methods={"POST"})
     */
    public function __invoke(MessageBusInterface $bus, Request $request, ChatService $serviceC, MessageService $serviceM): RedirectResponse
    {
        $this->serviceC = $serviceC;
        $this->serviceM = $serviceM;
        $mensaje = $request->request->get('message');
        $time = $request->request->get('time');
        $numPubli = $request->request->get('numPubli');
        $topicUsuario = 'http://monbarter/' .  $request->request->get('usuario2id');
        $topic = 'http://chat.monbarter/'  .  $this->service->ordenarId($this->getUser()->getId(), $request->request->get('usuario2id'));

        if ($numPubli == 0) {
            $publi = null;
        } else {
            $publi = $this->getDoctrine()->getRepository(Publicacion::class)->findOneBy(["id" => $request->request->get("idPubli")]);
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

        $this->serviceM->guardarMensaje($params);

        return $this->redirectToRoute('chat', [
            'id' => $request->request->get('usuario2id') . "_0",
        ]);
    }
}
