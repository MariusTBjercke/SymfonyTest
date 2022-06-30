<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Event\UserCreatedEvent;
use App\Exception\EmailAlreadyExistsException;
use App\Exception\UsernameAlreadyExistsException;
use App\Message\CreateUserMessage;
use App\Repository\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateUserMessageHandler implements MessageHandlerInterface {
    private UserRepository $userRepository;
    private EventDispatcherInterface $dispatcher;

    public function __construct(UserRepository $repository, EventDispatcherInterface $dispatcher) {
        $this->userRepository = $repository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Create a new user.
     *
     * @param CreateUserMessage $message
     * @return string|bool Return a string to indicate a failure or true to indicate success.
     */
    public function __invoke(CreateUserMessage $message): string|bool {
        // Return string if username or email already exists
        $user = $this->userRepository->findByUsername($message->getUsername());
        if ($user) {
            return 'username';
        }

        $user = $this->userRepository->findByEmail($message->getEmail());
        if ($user) {
            return 'email';
        }

        // Create user
        $user = (new User())
            ->setUsername($message->getUsername())
            ->setFirstname($message->getFirstname())
            ->setLastname($message->getLastname())
            ->setPassword($message->getPassword())
            ->setEmail($message->getEmail())
            ->setLoggedIn($message->isLoggedIn())
            ->setIsAdmin($message->isAdmin());

        $this->userRepository->add($user, true);

        // Fire event
        $this->dispatcher->dispatch(new UserCreatedEvent($user));

        return true;
    }
}
