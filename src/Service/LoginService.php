<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Exception\WrongPasswordException;
use App\Repository\UserRepository;
use App\Request\LoginRequest;
use Symfony\Component\HttpFoundation\RequestStack;

class LoginService {
    private UserRepository $repository;
    private RequestStack $requestStack;

    public function __construct(UserRepository $repository, RequestStack $requestStack) {
        $this->repository = $repository;
        $this->requestStack = $requestStack;
    }

    /**
     * @throws WrongPasswordException
     * @throws UserNotFoundException
     */
    public function login(LoginRequest $loginRequest): ?User {
        $user = $this->repository->findByUsername($loginRequest->username);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $password = $user->getPassword();

        if ($password !== $loginRequest->password) {
            throw new WrongPasswordException();
        }

        // Set session and login status
        if ($user instanceof User) {
            $user->setLoggedIn(true);

            $this->requestStack
                ->getCurrentRequest()
                ->getSession()
                ->set('user', $user);
        }

        return $user;
    }
}
