<?php

namespace App\Service;

use App\Message\CreateUserMessage;
use App\Request\CreateUserRequest;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateUserService {
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus) {
        $this->bus = $bus;
    }

    public function createUser(CreateUserRequest $request): void {
        $message = new CreateUserMessage(
            $request->username,
            $request->firstname,
            $request->lastname,
            $request->password,
            $request->email,
            false,
            false,
        );
        $this->bus->dispatch($message);
    }
}
