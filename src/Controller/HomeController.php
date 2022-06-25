<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    /**
     * @Route("/", name="home")
     */
    public function index(SessionInterface $session): void {
        $this->redirectToRoute('users');
//        return $this->render('home/home.html.twig', ['brukernavn' => $session->get('brukernavn')]);
    }



        /**
         * @Route("/home/add", name="add")
         */
    public function addUser(UserRepository $repository): JsonResponse {
        $newUser = new User();
        $repository->add($newUser, true);

        $response = [
            'status' => 'success',
            'message' => 'User added'
        ];


        $test = trim()



        return new JsonResponse($response);
    }

    /**
     * @Route("/home/users", name="users")
     */
    public function getUsers(UserRepository $repository): JsonResponse {
        $users = $repository->findAll();

        $response = [
            'result' => 'OK',
            'users' => $users,
        ];

        return new JsonResponse($response);
    }

}