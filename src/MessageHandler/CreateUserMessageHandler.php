<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\CreateUserMessage;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateUserMessageHandler implements MessageHandlerInterface
{
    public UserRepository $userRepository;

    public function __construct(UserRepository $repository)
    {
        $this->userRepository = $repository;
    }

    public function __invoke(CreateUserMessage $message): bool
    {
        $user = (new User())
            ->setName($message->getName())
            ->setEmail($message->getEmail())
            ->setAge($message->getAge());

        $this->userRepository->add($user, true);

        return true;
    }
}
