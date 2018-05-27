<?php
/**
 * User: Omar
 * Date: 27/05/2018
 * Time: 01:45
 */

namespace App\Repository;

use App\Model\Message;
use App\Model\User;
use Core\Repository\Repository;

class MessageRepository extends Repository
{
    public function getLastMessagesOrderByDateAsc($offset = 0, $limit = 10)
    {
        $query = "
            SELECT m.id, m.content, m.date, m.id_user, u.name, u.avatar_color FROM `messages` m
            LEFT JOIN `users` u ON m.id_user = u.id
            ORDER BY m.`date`Asc LIMIT {$offset}, {$limit}
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        /*$results = $stmt->fetchAll(\PDO::FETCH_FUNC, function($id, $content, $date, $id_user, $name) {
            $user = new User();
            $user
                ->setId($id_user)
                ->setName($name)
            ;
            $message = new Message();
            return $message
                ->setId($id)
                ->setContent($content)
                ->setDate($date)
                ->setAuthor($user)
            ;
        });*/
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $results;
    }
    public function save(Message $message)
    {
        $query = 'INSERT INTO `messages`(`content`, `date`, `id_user`) VALUES (:content, NOW(), :id_user)';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'content' => $message->getContent(),
            'id_user' => $message->getAuthor()->getId(),
        ]);
        $id = $this->pdo->lastInsertId();
        if ($message->getId()) {
            return $message;
        }
        return $message->setId($id);
    }
    protected function getModelClass()
    {
        return Message::class;
    }
}