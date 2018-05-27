<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 20:35
 */

namespace App\Controller;

use App\Model\User;
use App\Repository\UserRepository;
use Core\Controller\Controller;
use Core\Http\Request;

class LoginController extends Controller
{

    public function login()
    {
        if ($this->getUser()) {
            return $this->redirect('homepage');
        }
        /**
         * @var Request $request
         */
        $request = $this->get('request');
        $error = $request->getQueryParam('error');
        switch ($error) {
            case 'signin': $errorMsg = 'Nom d\'utilisateur ou le mot de passe est incorrect'; break;
            case 'register':
                $data = [];
                if (!$request->getQueryParam('username')){
                    $data[] = 'Nom d\'utilisateur';
                }
                if (!$request->getQueryParam('password')){
                    $data[] = 'Mot de passe';
                }
                if (!$request->getQueryParam('name')){
                    $data[] = 'Nom';
                }
                if (!$request->getQueryParam('gender')){
                    $data[] = 'Sexe';
                }
                $errorMsg = 'Les données suivantes doient être remplis: ' . implode(', ', $data);
                break;
            case 'exist':
                $error = 'register';
                $errorMsg = 'Le nom d\'utilisateur déjà est reservé';
                break;
            default:
                $errorMsg = '';
                break;
        }
        return $this->view('login', compact('error', 'errorMsg'));
    }
    public function signIn()
    {
        /**
         * @var Request $request
         */
        $request = $this->get('request');
        $username = $request->getPostParam('lg_username');
        $password = $request->getPostParam('lg_password');
        /**
         * @var UserRepository $repository
         */
        $repository = $this->get('UserRepository');
        /**
         * @var User $user
         */
        $user = $repository->findOneByUsername($username);
        if (!$user || !$this->get('passwordEncoder')->verify($password, $user->getPassword())) {
            return $this->redirect('login?error=signin');
        }
        $user->setActive(1);
        $repository->save($user);
        $this->get('session')->set('user', serialize($user));
        return $this->redirect('homepage');
    }
    public function register()
    {
        /**
         * @var Request $request
         */
        $request = $this->get('request');
        $name = trim($request->getPostParam('reg_name'));
        $username = trim($request->getPostParam('reg_username'));
        $password = trim($request->getPostParam('reg_password'));
        $gender = (int)$request->getPostParam('reg_gender');
        if (!$name || !$username || !$password || !$gender) {
            $inputs = compact('name', 'username', 'password', 'gender');
            $this->redirect('login?error=register&' . http_build_query($inputs));
        }
        $user = new User();
        $user
            ->setName($name)
            ->setPassword($this->get('passwordEncoder')->encode($password))
            ->setUsername($username)
            ->setGender($gender)
            ->setAvatarColor(substr(sprintf('#%06X', mt_rand(0, 0xFFFFFF)), 1))
            ->setRoles(['ROLE_USER'])
            ->setActive(1)
        ;
        /**
         * @var UserRepository $repository
         */
        $repository = $this->get('UserRepository');
        try {
            $repository->save($user);
        } catch (\PDOException $e) {
            $this->redirect('login?error=exist');
        }
        $this->get('session')->set('user', serialize($user));
        return $this->redirect('homepage');
    }
    public function logout()
    {
        $repository = $this->get('UserRepository');
        $user = $this->getUser();
        $user->setActive(0);
        $repository->save($user);
        $this->get('session')->remove('user');
        return $this->redirect('login');
    }

}