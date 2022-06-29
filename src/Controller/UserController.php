<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Message\CreateUserMessage;
use App\Repository\UserRepository;
use App\Request\CreateUserRequest;
use App\Service\CreateUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController {
    //    #[Route('/users', name: 'user_index', methods: ['GET'])]
    //    public function index(UserRepository $userRepository): Response {
    //        return $this->render('pages/register/index.html.twig', [
    //            'users' => $userRepository->findAll(),
    //        ]);
    //    }

    /**
     * @Route("/register", name="user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CreateUserService $createUserService): Response {
        $user = new User();

        $createUserRequest = new CreateUserRequest();

        $form = $this->createForm(UserType::class, $createUserRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createUserService->createUser($createUserRequest);

            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/register/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    //
    //    #[Route('/user/{id}', name: 'user_show', methods: ['GET'])]
    //    public function show(User $user): Response {
    //        return $this->render('pages/register/show.html.twig', [
    //            'user' => $user,
    //        ]);
    //    }
    //
    //    #[Route('/user/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    //    public function edit(Request $request, User $user, UserRepository $userRepository): Response {
    //        $form = $this->createForm(UserType::class, $user);
    //        $form->handleRequest($request);
    //
    //        if ($form->isSubmitted() && $form->isValid()) {
    //            $userRepository->add($user, true);
    //
    //            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    //        }
    //
    //        return $this->renderForm('pages/register/edit.html.twig', [
    //            'user' => $user,
    //            'form' => $form,
    //        ]);
    //    }
    //
    //    #[Route('/user/{id}', name: 'user_delete', methods: ['POST'])]
    //    public function delete(Request $request, User $user, UserRepository $userRepository): Response {
    //        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
    //            $userRepository->remove($user, true);
    //        }
    //
    //        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    //    }
}
