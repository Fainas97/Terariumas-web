<?php

namespace App\Controller;

use App\Entity\Message;
use App\Service\MessageService;
use App\Service\TerrariumService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/messages", name="messages_page")
     * @IsGranted("ROLE_ADMIN", message="Tik prižiūrinčios įmonės teises turinti paskyra gali pasiekti ši puslapį")
     * @param MessageService $messageService
     * @return Response
     */
    public function index(MessageService $messageService)
    {
        $messages = $messageService->getTerrariumsMessages();

        return $this->render('message/messages.html.twig', [
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/messages/table", name="messages_table", options={"expose" = true})
     * @IsGranted("ROLE_ADMIN", message="Tik prižiūrinčios įmonės teises turinti paskyra gali pasiekti ši puslapį")
     * @param MessageService $messageService
     * @return Response
     */
    public function messagesTable(MessageService $messageService)
    {
        $messages = $messageService->getTerrariumsMessages();

        return new Response($this->renderView('table/message-table.html.twig', [
            'messages' => $messages
        ]));
    }

    /**
     * @Route("/message/data")
     * @param Request $request
     * @param TerrariumService $terrariumService
     * @param MessageService $messageService
     * @return Response
     */
    public function receiveMessage(Request $request, TerrariumService $terrariumService, MessageService $messageService)
    {
        $receivedRequest = $request->request->all();
        $terrarium = $terrariumService->getTerrariumByAuth($receivedRequest['auth']);

        if ($terrarium) {
            $this->saveMessageData($receivedRequest, $terrarium->getId());

            return new JsonResponse(array('success'));
        }

        return new JsonResponse(array('false'));
    }

    private function saveMessageData(array $receivedRequest, int $id)
    {
        $message = new Message();
        $message->setTerrariumId($id);
        $message->setMessage($receivedRequest['message']);
        $message->setTime(new \DateTime('now'));
        $message->setActive($receivedRequest['active']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($message);
        $entityManager->flush();
    }

}