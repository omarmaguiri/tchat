<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 13:12
 */

namespace App\Controller;

use App\Model\Message;
use App\Repository\MessageRepository;
use Core\Controller\Controller;
use Core\Http\Request;

class MessageController extends Controller
{
    public function listMessages()
    {
        /**
         * @var MessageRepository $repository
         */
        $repository = $this->get('MessageRepository');
        $messages = $repository->getLastMessagesOrderByDateAsc(0, 100);
        return $this->json([
                'user' => [
                    'id' => $this->getUser()->getId(),
                ],
                'messages' => $messages
            ]
        );
    }
    public function addMessages()
    {
        /**
         * @var MessageRepository $repository
         */
        $repository = $this->get('MessageRepository');
        /**
         * @var Request $request
         */
        $request = $this->get('request');
        $content = $request->getPostParam('message');
        $message = new Message();
        $message
            ->setAuthor($this->getUser())
            ->setContent($content)
        ;
        $repository->save($message);
        return $this->json(['Success' => true]);
    }
}