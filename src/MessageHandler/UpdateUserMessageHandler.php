<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\UpdateUserMessage;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateUserMessageHandler implements MessageHandlerInterface
{
    public UserRepository $userRepository;

    public function __construct(UserRepository $repository)
    {
        $this->userRepository = $repository;
    }

    public function __invoke(UpdateUserMessage $message): string
    {
        $user = (new User())
            ->setName($message->getName())
            ->setEmail($message->getEmail())
            ->setAge($message->getAge());

        $this->userRepository->add($user, true);

        return "Svar fra handler";
    }
}
