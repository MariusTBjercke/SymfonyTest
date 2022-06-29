<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Exception\WrongPasswordException;
use App\Form\UserType;
use App\Message\CreateUserMessage;
use App\Repository\UserRepository;
use App\Request\CreateUserRequest;
use App\Request\LoginRequest;
use App\Service\CreateUserService;
use App\Service\LoginService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController {
    /**
     * @Route("/register", name="user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CreateUserService $createUserService): Response {
        $user = new User();

        $createUserRequest = new CreateUserRequest();

        $form = $this->createForm(UserType::class, $createUserRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $createUserService->createUser($createUserRequest);

            if (!$result) {
                $this->addFlash('error', 'Username or email already exists.');
                return $this->redirectToRoute('user_new');
            }

            $this->addFlash('success', 'User created successfully.');
            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/register/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/login", name="user_login", methods={"GET", "POST"})
     */
    public function login(Request $request, LoginService $loginService): Response {
        $loginRequest = new LoginRequest();

        $form = $this->createFormBuilder($loginRequest)
            ->add('username')
            ->add('password')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $loginService->login($loginRequest);
            } catch (WrongPasswordException | UserNotFoundException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('user_login');
            }

            $this->addFlash('success', 'Login successful.');
            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/login/index.html.twig', [
            'form' => $form,
        ]);
    }
}
