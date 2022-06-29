<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Event\UserCreatedEvent;
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

    public function __invoke(CreateUserMessage $message): bool {
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
