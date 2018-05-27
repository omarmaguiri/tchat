<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 15:54
 */

namespace App\Controller;

use App\Repository\UserRepository;
use Core\Controller\Controller;

class HomeController extends Controller
{

    public function index()
    {
        return $this->view('tchat');
    }

    public function listUsers()
    {
        /**
         * @var UserRepository $repository
         */
        $repository = $this->get('UserRepository');
        $users = $repository->findAllActiveExcept($this->getUser()->getId());
        return $this->json($users);
    }

}