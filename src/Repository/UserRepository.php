<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 21:58
 */

namespace App\Repository;

use App\Model\User;
use Core\Repository\Repository;

class UserRepository extends Repository
{
    public function findOneByUsername($username)
    {
        $query = 'SELECT * FROM `users` u WHERE u.`username` = :username';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'username' => $username,
        ]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $this->getModelClass());
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result;
    }
    public function findAllActiveExcept($id)
    {
        $query = 'SELECT * FROM `users` u WHERE u.`active` = :active AND u.id <> :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'active' => 1,
            'id' => $id,
        ]);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $results = $stmt->fetchAll();
        $stmt->closeCursor();
        return $results;
    }
    public function save(User $user)
    {
        $query = 'INSERT INTO `users`(`name`, `username`, `password`, `gender`, `active`, `avatar_color`, `roles`) VALUES (:name, :username, :password, :gender, :active, :avatar_color, :roles)';
        if ($user->getId()) {
            $query = 'UPDATE `users` SET `name`= :name, `username`= :username, `password`= :password, `gender`= :gender, `active`= :active, `avatar_color`= :avatar_color, `roles`= :roles WHERE `id` = :id';
        }
        $stmt = $this->pdo->prepare($query);
        $params = [
            'name' => $user->getName(),
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
            'gender' => $user->getGender(),
            'active' => $user->getActive(),
            'roles' => serialize($user->getRoles()),
            'avatar_color' => $user->getAvatarColor(),
        ];
        if ($user->getId()) {
            $params['id'] = $user->getId();
        }
        $stmt->execute($params);
        $id = $this->pdo->lastInsertId();
        if ($user->getId()) {
            return $user;
        }
        return $user->setId($id);
    }
    protected function getModelClass()
    {
        return User::class;
    }
}